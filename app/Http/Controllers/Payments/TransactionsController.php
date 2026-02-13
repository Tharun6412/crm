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
use Faker\Provider\Payment;
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
            'status_id' => $transaction->transaction_status_id,
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
        if($transaction->transaction_status_id == TransactionStatus::SUCCESS->value) {
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
                'notes' => 'required',
            ]);
        }
        // Global Data Preparation
        $global_details = [
            'transaction' => $transaction,
            'notes' => $request->notes ?? null,
            'amount' => $request->amount ?? null,
            'transaction_no' => $request->transaction_no ?? null,
            'bank_ref' => $request->bank_ref ?? null,
            'payment_mode' => $request->payment_mode ?? null,
            'invoice_id' => $transaction->invoice_id,
        ];
        if(!empty($request->transaction_status) and $request->transaction_status == TransactionStatus::SUCCESS->value) {
            $result = $this->processSuccessfulTransaction($global_details);
        }else {
            $result = $this->processFailedTransaction($global_details);
        }
        // Response
        if(isset($result['status']) and $result['status'] == true) {
            return response()->json(['success' => $result['message']]);
        }else {
            return response()->json(['success' => $result['message'] ?? 'Error found in transaction']);
        }
    }
    
    /**
     * Success
     */
    public function processSuccessfulTransaction($global_details)
    {
        $transaction = $global_details['transaction'];
        // success transactions
        switch($transaction->payment_module_id) {
            case 1: //Pay Deposit
                $result = ['status' => true, 'message' => 'Module under progress'];
                break;
            case 2: //Security Deposit
                $result = $this->processSecurityDeposit($global_details);
                break;
            case 3://Gas Bill
            case 4: //Invoice Payments
                $result = $this->processInvoicePayment($transaction->id, $global_details);
                break;
            case 5: //Recharges
                // Array Preparation
                $transaction_data['recharge'] = [
                    'consumer_id' => $transaction->consumer_id,
                    'ca_num' => $transaction->consumer->crn,
                    'amount' => $global_details['amount'],
                    'ref_num' => $global_details['transaction_no'],
                    'utr_num' => $global_details['bank_ref'],
                    'trans_date' => $transaction->transaction_date,
                    'mobile_num' => '+91'. $transaction->consumer->phone,
                ];
                $result = $this->processRecharge($transaction_data['recharge'], $transaction->id);
                break;
            default;
        }
        // Update Transaction
        if(isset($result) and $result['status'] == true) {
            $data = [
                'transaction_status_id' => TransactionStatus::SUCCESS->value,
                'payment_mode' => $global_details['payment_mode'],
                'bank_ref' => $global_details['bank_ref'],
                'paid_amount' => $global_details['amount'],
                'remarks' => $global_details['notes'],
            ];
            $transaction->update($data);
        }
        return $result;
    }

    /**
     * Fail
     */
    public function processFailedTransaction($global_details)
    {
        $transaction = $global_details['transaction'];
        switch($transaction->payment_module_id) {
            case 1: //Pay Deposit
                $result = ['status' => true, 'message' => 'Module under progress'];
                break;
            case 2: //Security Deposit
                // Data Preparation
                $result = $this->reverseSecurityDeposit($global_details);
                break;
            case 3://Gas Bill
                $result = $this->reverseGasInvoicePayment($transaction->id, $global_details);
            case 4: //Invoice Payments
                $result = $this->reverseInvoicePayment($transaction->id, $global_details);
                break;
            case 5: //Recharges
                // Array Preparation
                $transaction_data['recharge'] = [
                    'ca_num' => $transaction->consumer->crn,
                    'meter_serial_no' => $transaction->consumer->activeMeter?->meter_serial_no,
                    'amount' => $transaction->amount,
                    'ref_num' => $transaction->transaction_id,
                    'utr_num' => $transaction->bank_ref,
                    'trans_date' => $transaction->transaction_date,
                ];
                $result = $this->reverseRecharge($transaction_data['recharge'], $transaction->id);
                break;
            default;
        }
        // Update Transaction
        if(isset($result['status']) and $result['status'] == true) {
            $data = [
                'transaction_status_id' => TransactionStatus::FAIL->value,
                'paid_amount' => 0,
                'remarks' => $global_details['notes'],
            ];
            $transaction->update($data);
        }
        return $result;
    }
    /**
     * Payment Reversal
     * @param $id
     */
    public function reverseGasInvoicePayment(int $id, array $global_data)
    {
        // fetch Payment record
        $payments = InvoicePayment::where('pay_transaction_id', $id)->where('status_id', PaymentStatus::COMPLETED->value)->where('amount', '>', 0)->orderBy('id', 'desc')->get();
        $flag = true;
        if($payments->count() > 0) {
            // using foreach loop
            foreach($payments as $payment) {
                $payment_data = [
                    'payment' => $payment,
                    'notes' => $global_data['notes'],
                ];
                $response = PaymentService::reversal($payment_data);
                if(!$response) {
                    $flag = false;
                    break;
                }
            }
            // Call Payment Service - Reversal 
            if($flag) {
                return [
                    'status' => true,
                    'message' => "Payment reversed successfully",
                ];
            }else {
                return [
                    'status' => false,
                    'message' => "Some payments not found",
                ];
            }
        }
        return [
            'status' => false,
            'message' => "Payment not reversed", 
        ];
    }
    /**
     * Payment Success
     * @param $id
     */
    public function processInvoicePayment(int $id, array $global_data)
    {
        // Call Payment service
        $payment = PaymentService::create([
            'invoice_id' => $global_data['invoice_id'] ?? null,
            'payment_date' => Carbon::now()->toDateString(),
            'payment_type_id' => 2,
            'transaction_id' => $global_data['transaction_no'],
            'pay_transaction_id' => $id,
            'amount' => $global_data['amount'],
            'status_id' => PaymentStatus::COMPLETED->value,
            'notes' => $global_data['notes'],
            'created_by' => Auth::id(),
        ]);
        if($payment->id) {
            // response
            return [
                'status' => true,
                'message' => "Payment updated successfully",
            ];
        }else {
            return [
                'status' => false,
                'message' => 'Error in updating Payment',
            ];
        }
    }
    /**
     * Payment Reversal
     * @param $id
     */
    public function reverseInvoicePayment(int $id, array $global_data)
    {
        // fetch Payment record
        $payment = InvoicePayment::where('pay_transaction_id', $id)->orderBy('id', 'desc')->first();
        if($payment) {
            $payment_data = [
                'payment' => $payment,
                'notes' => $global_data['notes'],
            ];
            // Call Payment Service - Reversal 
            $response = PaymentService::reversal($payment_data);
            if(isset($response) and $response == true) {
                return [
                    'status' => true,
                    'message' => "Payment reversed successfully",
                ];
            }else {
                return [
                    'status' => false,
                    'message' => "No payment details found",
                ];
            }
        }
        return [
            'status' => false,
            'message' => "Payment not reversed", 
        ];
    }

    /**
     * Recharge Success
     * @param array $transactiondata, int $id
     */
    public function processRecharge(array $transaction_data, int $id)
    {
        // Recharge Data Preparation
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
        //-- Send data to Polaris HES
        $recharge_success = new Recharge;
        $response = $recharge_success->push($recharge_data);
        $response_data = $response->json();
        // response
        if($response_data['recharge_response']['error_code'] == 1) {
            return [
                'status' => false,
                'message' => $response_data['recharge_response']['message'],
            ];
        }else {
            // Add Recharge record
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
            //response 
            return [
                'status' => true,
                'message' => "Recharge success",
            ];
        }
    }

    /**
     * Recharge Fail
     * @param $id
     */
    public function reverseRecharge(array $transaction_data, int $id)
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
        //-- Send data to Polaris HES
        $recharge_success = new Recharge;
        $response = $recharge_success->cancelRecharge($recharge_cancel_data);
        $response_data = $response->json();
        if($response_data['responseCode'] == 404) {
            return [
                'status' => false,
                'message' => "Consumer recharge data not found",
            ];
        }else {
            $recharge = PayRecharge::where('transaction_id', $id)->first();
            if($recharge) {
                // Update Payment record
                $recharge->update([
                    'amount' => $recharge->balance,
                    'balance' => $recharge->amount,
                    'status_id' => PaymentStatus::REVERSAL->value,
                    'updated_by' => Auth::id(),
                ]);
            }
            // response
            return [
                'status' => true,
                'message' => "Recharge updated to failed successfully", 
            ];
        }
    }

    /**
     * SD Success
     * @param $id
     */
    public function processSecurityDeposit(int $id)
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
    public function reverseSecurityDeposit(int $id)
    {
        // fetch Payment record
        return [
            'status' => true,
            'message' => 'SD payment updated successfuly',
        ];
    }
}