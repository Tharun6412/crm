<?php

namespace App\Http\Controllers\Payments;

use App\Contracts\Prepaid\Recharge;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\PaymentReversal;
use App\Models\Master\PaymentTransactionStatus;
use App\Models\Master\PaymentType;
use App\Models\Payments\PaymentTransaction;
use App\Models\Payments\PayRecharge;
use App\Services\LedgerService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get all transactions
        $transactions = PaymentTransaction::
            when($request->has('key'), function ($q) use($request) {
                $q->whereAny(['transaction_id', 'amount', 'transaction_ref', 'bank_ref', 'pg_ref_id','payment_mode'], 'like', '%' . $request->key . '%');
                $q->orWhereHas('consumer', function ($q) use($request) {
                    $q->where('crn', 'like', '%' . $request->key . '%');
                });
            })
            ->when($request->has('geo_area'), function($q) use($request) {
                $q->whereHas('consumer', function($q) use($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                });
            })
            ->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        // Render output
        if($request->ajax())
            return view('payments.transactions.list-body', ['transactions' => $transactions]);
        else
            return view('payments.transactions.list', ['transactions' => $transactions]);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get transaction details
        $transaction = PaymentTransaction::find($id);

        // Render output
        return view('payments.transactions.show', ['transaction' => $transaction]);
    }

    /**
     * Edit
     */
    public function edit(Request $request, $id)
    {
        $transaction = PaymentTransaction::find($id);
        $transaction_status = PaymentTransactionStatus::whereIN('id', [TransactionStatus::SUCCESS->value, TransactionStatus::FAIL->value])->get();
        return view('payments.transactions.edit', [
            'transaction' => $transaction,
            'transaction_status' => $transaction_status,
            'status' => $request->status_id,
        ]);
    }

    /**
     * Update Transaction Status
     * @param $transactionID
     */
    public function update(Request $request, $id)
    {
        // Fetch Transaction Details
        $transaction = PaymentTransaction::find($id);
        if(in_array($transaction->transaction_status_id, [TransactionStatus::SUCCESS->value, TransactionStatus::INITIATED->value])) {
            // Validation
            $request->validate([
                'notes' => 'required',
            ]);
        }else {
            $request->validate([
                'transaction_status' => 'required',
                'amount' => 'required|numeric|min:'.$transaction->amount.'|max:'.$transaction->amount,
                'payment_mode' => 'required',
                'bank_ref' => 'required',
            ]);
        }
        // Global Data Preparation
        $global_details = [
            'notes' => $request->notes ?? null,
            'amount' => $request->amount ?? null,
        ];
        // Check Transaction Status
        $transaction_status_id = $request->filled('transaction_status') ? $request->transaction_status : TransactionStatus::FAIL->value;
        $isSuccess = $transaction_status_id == TransactionStatus::SUCCESS->value;
        // Get Transaction Module
        switch($transaction->payment_module_id) {
            case 1: //Pay Deposit
                $result = ['status' => true, 'message' => 'Module under progress'];
                break;
            case 2: //Security Deposit
                // Data Preparation
                $result = $isSuccess ? $this->sdSuccess($transaction->id, $global_details) : $this->sdFail($transaction->id, $global_details);
                break;
            case 3://Gas Bill
            case 4: //Invoice Payments
                $result = $isSuccess ? $this->paymentSuccess($transaction->id, $global_details) : $this->paymentReversal($transaction->id, $global_details);
                break;
            case 5: //Recharges
                // Array Preparation
                $transaction_data['recharge'] = [
                    'consumer_id' => $transaction->consumer_id,
                    'ca_num' => $transaction->consumer->crn,
                    'meter_serial_no' => $transaction->consumer->activeMeter?->meter_serial_no,
                    'amount' => (!empty($request->amount)) ? $request->amount : $transaction->amount,
                    'ref_num' => (!empty($request->transaction_no)) ? $request->transaction_no : $transaction->transaction_id,
                    'utr_num' => (!empty($request->bank_ref)) ? $request->bank_ref : $transaction->bank_ref,
                    'trans_date' => $transaction->transaction_date,
                    'mobile_num' => '+91'. $transaction->consumer->phone,
                    'notes' => $request->notes ?? null,
                ];
                $result = $isSuccess ? $this->rechargeSuccess($transaction_data['recharge'], $transaction->id) : $this->rechargeFail($transaction_data['recharge'], $transaction->id);
                break;
            default;
        }
        // Response
        if(isset($result['status']) and $result['status']) {
            // Transaction Update
            if($isSuccess) {
                $data = [
                    'transaction_status_id' => TransactionStatus::SUCCESS->value,
                    'payment_mode' => $request->payment_mode,
                    'bank_ref' => $request->bank_ref,
                    'paid_amount' => $request->amount,
                ];
            }else {
                $data = [
                    'transaction_status_id' => TransactionStatus::FAIL->value,
                    'paid_amount' => 0,
                ];
            }
            $transaction->update($data);
            return response()->json(['success' => $result['message']]);
        }else {
            return response()->json(['success' => 'Error found in transaction']);
        }
    }

    /**
     * Payment Success
     * @param $id
     */
    public function paymentSuccess(int $id, array $global_data)
    {
        // fetch Payment record 
        $payment = InvoicePayment::where('pay_transaction_id', $id)->get()->first();
        if($payment) {
            // Add to Ledger Record
            $ledger_data = [
                'model' => $payment,
                'consumer_id' => $payment->invoice->consumer_id,
                'amount' => $global_data['amount'],
            ];
            $add_ledger = LedgerService::create($ledger_data, 'cr');
            // Update Payment record
            $payment->update([
                'amount' => $global_data['amount'],
                'balance' => 0,
                'status_id' => PaymentStatus::COMPLETED->value,
            ]);
            // Check if it is GAS Invoice
            if($payment->invoice->invoice_type == InvoiceType::GAS_BILL->value) {
                if($payment->invoice->childInvoices->isNotEmpty()) {
                    foreach($payment->invoice->childInvoices as $childInvoice) {
                        $childInvoice->update([
                            'paid_amount' => $childInvoice->payable_amount,
                            'balance_amount' => 0,
                            'status_id' => InvoiceStatus::PAID->value,
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }
            }
            // Bill Main Invoice Update
            $payment->invoice->update([
                'paid_amount' => $global_data['amount'],
                'balance_amount' => 0,
                'status_id' => InvoiceStatus::PAID->value,
                'updated_by' => Auth::id(),
            ]);
            // response
            return [
                'status' => true,
                'message' => "Payment updated successfully",
            ];
        }else {
            return [
                'status' => false,
                'message' => "Payment not updated",
            ];
        }
    }
    /**
     * Payment Reversal
     * @param $id
     */
    public function paymentReversal(int $id, array $global_data)
    {
        // fetch Payment record
        $payment = InvoicePayment::where('pay_transaction_id', $id)->get()->first();
        if($payment) {
            // Add to Ledger Record
            $ledger_data = [
                'model' => $payment,
                'consumer_id' => $payment->invoice->consumer_id,
                'amount' => $payment->amount,
            ];
            $add_ledger = LedgerService::create($ledger_data, 'dr');
            // Update Payment record
            $payment->update([
                'amount' => 0,
                'balance' => $payment->amount,
                'status_id' => PaymentStatus::REVERSAL->value,
            ]);
            // Add Payment Reversal record
            PaymentReversal::create([
                'payment_id' => $id,
                'notes' => $global_data['notes'],
                'created_by' => Auth::id(),
            ]);
            // Bill Invoice Update
            $payment->invoice->update([
                'paid_amount' => 0,
                'balance_amount' => $payment->invoice->payable_amount,
                'status_id' => InvoiceStatus::NOT_PAID->value,
                'updated_by' => Auth::id(),
            ]);
        }
        // response
        return [
            'status' => true,
            'message' => "Payment reversed successfully",
        ];
    }

    /**
     * Recharge Success
     * @param array $transactiondata, int $id
     */
    public function rechargeSuccess(array $transaction_data, int $id)
    {
        // Data Preparation
        $recharge_data = [
            'recharge_request' => [
                'ca_num' => $transaction_data['ca_num'],
                'amount' => $transaction_data['amount'],
                'ref_num' => $transaction_data['ref_num'],
                'utr_num' => $transaction_data['utr_num'],
                'trans_date' => Carbon::now()->toDateString(),
                'mobile_num' => $transaction_data['mobile_num'],
            ]
        ];
        // fetch Payment record
        $recharge = PayRecharge::where('transaction_id', $id)->get()->first();
        if($recharge) {
            // Update Recharge 
            $recharge->update([
                'amount' => $recharge->balance,
                'balance' => $recharge->amount,
                'status_id' => PaymentStatus::COMPLETED->value,
                'updated_by' => Auth::id(),
            ]);
        }else {
            PayRecharge::create([
                'consumer_id' => $transaction_data['consumer_id'],
                'recharge_date' => Carbon::now()->toDateString(),
                'amount' => $transaction_data['amount'],
                'balance' => 0,
                'payment_type_id' => 6,
                'transaction_id' => $id,
                'status_id' => PaymentStatus::COMPLETED->value,
                'created_by' => Auth::id(),
            ]);
        }
        //-- Send data to Polaris HES
        $recharge_success = new Recharge;
        $response = $recharge_success->push($recharge_data);
        $response_data = $response->json();
        // response
        return [
            'status' => true,
            'message' => $response_data['recharge_response']['message'],
        ];
    }

    /**
     * Recharge Fail
     * @param $id
     */
    public function rechargeFail(array $transaction_data, int $id)
    {
        // Data Preparation
        $recharge_cancel_data = array(
            'crn' => $transaction_data['ca_num'],
            'meter_serial_no' => $transaction_data['meter_serial_no'],
            'utr_num' => $transaction_data['ref_num'],
            'ref_num' => $transaction_data['utr_num'],
            'trans_date' => $transaction_data['trans_date'],
            'amount' => $transaction_data['amount'],
        );
        // fetch Payment record
        $recharge = PayRecharge::where('transaction_id', $id)->get()->first();
        if($recharge) {
            // Update Payment record
            $recharge->update([
                'amount' => $recharge->balance,
                'balance' => $recharge->amount,
                'status_id' => PaymentStatus::REVERSAL->value,
                'updated_by' => Auth::id(),
                'remarks' => $transaction_data['notes'],
            ]);
        }
        //-- Send data to Polaris HES
        $recharge_success = new Recharge;
        $response = $recharge_success->cancelRecharge($recharge_cancel_data);
        $response_data = $response->json();
        // response
        return [
            'status' => true,
            'message' => $response_data['data']['result']['message'] ?? $response_data['data']['error']['data'], 
        ];
    }

    /**
     * SD Success
     * @param $id
     */
    public function sdSuccess(int $id)
    {
        // fetch Payment record
        return [
            'status' => true,
            'message' => 'SD payment updated successfully',
        ];
    }

    /**
     * SD Fail
     * @param $id
     */
    public function sdFail(int $id)
    {
        // fetch Payment record
        return [
            'status' => true,
            'message' => 'SD payment updated successfuly',
        ];
    }
}