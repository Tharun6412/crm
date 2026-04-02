<?php

namespace App\Http\Controllers\Payments;

use App\Contracts\Prepaid\Recharge;
use App\Enums\Constants;
use App\Enums\InvoiceItem as EnumsInvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\SegmentType;
use App\Enums\TaxType;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\PaymentReversal;
use App\Models\Master\PaymentTransactionStatus;
use App\Models\Payments\PaymentTransaction;
use App\Models\Payments\PayRecharge;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use App\Services\PaymentService;
use App\Services\RechargeService;
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
            when($request->has('payment_modules'), function ($q) use($request) {
                $q->whereIn('payment_module_id', $request->payment_modules);
            })
            ->when($request->has('payment_gateways'), function ($q) use($request) {
                $q->whereIn('gateway_id', $request->payment_gateways);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function ($q) use($request) {
                $q->whereBetween('transaction_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->toDateTimeString()]);
            })
            ->when(($request->has('key') and !empty($request->key)), function ($q) use($request) {
                $q->where(function ($q) use($request) {
                    $q->whereAny(['transaction_id', 'amount', 'transaction_ref', 'bank_ref', 'pg_ref_id','payment_mode'], 'like', '%' . $request->key . '%');
                    $q->orWhereHas('consumer', function ($q) use($request) {
                        $q->where('crn', 'like', '%' . $request->key . '%');
                    });
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
        $request->validate([
            'transaction_status_id' => 'required',
            'notes' => 'required|max:128',
        ]);
        // Fetch Transaction Details
        $transaction = PaymentTransaction::find($id);
        if($request->transaction_status_id == TransactionStatus::SUCCESS->value) {
            $request->validate([
                'amount' => 'required|numeric|min:'.$transaction->amount.'|max:'.$transaction->amount,
                'payment_mode' => 'required',
                'bank_ref' => 'required',
            ]);
        }
        // Global Data Preparation
        $transaction_details = [
            'notes' => $request->notes ?? null,
            'amount' => $request->amount ?? null,
            'transaction_no' => $transaction->transaction_id ?? null,
            'bank_ref' => $request->bank_ref ?? null,
            'payment_mode' => $request->payment_mode ?? null,
            'invoice_id' => $transaction->invoice_id,
        ];
        if($request->transaction_status_id == TransactionStatus::SUCCESS->value) {
            $result = $this->processSuccessfulTransaction($transaction, $transaction_details);
            // Update Transaction Table
            if(isset($result['status']) and $result['status'] == 1) {
                $transaction->update([
                    'transaction_status_id' => TransactionStatus::SUCCESS->value,
                    'payment_mode' => $transaction_details['payment_mode'],
                    'bank_ref' => $transaction_details['bank_ref'],
                    'paid_amount' => $transaction_details['amount'],
                    'remarks' => $transaction_details['notes'],
                ]);
            }
        }else {
            $result = $this->processFailedTransaction($transaction, $transaction_details);
            // Update Transaction Table
            if(isset($result['status']) and $result['status'] == 1) {
                $transaction->update([
                    'transaction_status_id' => TransactionStatus::FAIL->value,
                    'paid_amount' => 0,
                    'remarks' => $transaction_details['notes'],
                ]);
            }
        }
        // Response
        if(isset($result['status']) and $result['status'] == 1) {
            return response()->json(['success' => $result['message']]);
        }else {
            return response()->json(['success' => $result['message'] ?? 'Error found in transaction']);
        }
    }
    
    /**
     * Success
     */
    public function processSuccessfulTransaction($transaction, $transaction_details)
    {
        // success transactions
        switch($transaction->payment_module_id) {
            case 1: //Pay Deposit
                $result = ['status' => true, 'message' => 'Module under progress'];
                break;
            case 2: //Security Deposit
                $result = $this->processSecurityDeposit($transaction_details);
                break;
            case 3://Gas Bill
                $result = $this->processGasInvoicePayment($transaction->id, $transaction_details);
                break;
            case 4: //Invoice Payments
                $result = $this->processInvoicePayment($transaction->id, $transaction_details);
                break;
            case 5: //Recharges
                // Array Preparation
                $transaction_data['recharge'] = [
                    'consumer_id' => $transaction->consumer_id,
                    'ca_num' => $transaction->consumer->crn,
                    'amount' => $transaction_details['amount'],
                    'ref_num' => $transaction_details['transaction_no'],
                    'utr_num' => $transaction_details['bank_ref'],
                    'trans_date' => $transaction->transaction_date,
                    'mobile_num' => '+91'. $transaction->consumer->phone,
                ];
                $result = $this->processRecharge($transaction_data['recharge'], $transaction->id);
                break;
            default;
        }
        // response
        return $result;
    }

    /**
     * Fail
     */
    public function processFailedTransaction($transaction, $transaction_details)
    {
        switch($transaction->payment_module_id) {
            case 1: //Pay Deposit
                $result = ['status' => true, 'message' => 'Module under progress'];
                break;
            case 2: //Security Deposit
                // Data Preparation
                $result = $this->reverseSecurityDeposit($transaction_details);
                break;
            case 3://Gas Bill
                $result = $this->reverseGasInvoicePayment($transaction->id, $transaction_details);
            case 4: //Invoice Payments
                $result = $this->reverseInvoicePayment($transaction->id, $transaction_details);
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
        // response
        return $result;
    }

    /**
     * Payment Success
     * @param $id
     */
    public function processGasInvoicePayment(int $id, array $transaction_details)
    {
        $invoice = BillInvoice::find($transaction_details['invoice_id']);
        // Check LPC applicable
        $lpc = 0;
        if(Carbon::parse($invoice->due_date)->isBefore(Carbon::today()))
            if($invoice->childInvoices->where('type_id', InvoiceType::LATE_PAYMENT_CHARGES->value)->count() <= 0)
                $lpc = Constants::DPNG_LPC->value;
        // Call Payment service
        $payment = PaymentService::create([
            'invoice_id' => $invoice->id,
            'payment_date' => Carbon::now()->toDateString(),
            'payment_type_id' => PaymentType::ONLINE->value,
            'transaction_id' => $transaction_details['bank_ref'],
            'pay_transaction_id' => $id,
            'amount' => $invoice['payable_amount'],
            'status_id' => PaymentStatus::COMPLETED->value,
            'notes' => $transaction_details['notes'],
            'created_by' => Auth::id(),
        ]);
        // Generate Latepayment charges if LPC > 0
        if($lpc > 0) {
            // Invoice Item
            if($invoice->consumer->segment_id == SegmentType::DOMESTIC->value) {
                $inv_item = EnumsInvoiceItem::DLPC->value;
            }else if($invoice->consumer->segment_id == SegmentType::COMMERCIAL->value) {
                $inv_item = EnumsInvoiceItem::CLPC->value;
            }else {
                $inv_item = EnumsInvoiceItem::ILPC->value;
            }
            $gst_calculated_amt = 1.18; //(1+18%)
            $base_amt = round($lpc / $gst_calculated_amt, 3);
            $tax_amt = round($lpc - $base_amt, 3);
            $invoice_items[] = [
                'item_id' => $inv_item,
                'quantity' => 1,
                'unit_price' => $base_amt,
                'total_price' => $base_amt,
                'created_at' => Carbon::now(),
            ];
            $invoice_data = [
                'config' => [
                    'state_id' => $invoice->consumer->state_id,
                    'tax_id' => TaxType::GST->value, //GST = 2
                ],
                'headers' => [
                    'type_id' => InvoiceType::LATE_PAYMENT_CHARGES->value, // 3 = LPC
                    'consumer_id' => $invoice->consumer_id,
                    'invoice_date' => Carbon::now()->toDateString(),
                    'base_amount' => $base_amt,
                    'taxable_amount' => $base_amt,
                    'tax_id' => TaxType::GST->value,
                    'tax_value' => 18,
                    'tax_amount' => $tax_amt,
                    'total_amount' => $lpc,
                    'payable_amount' => $lpc,
                    'paid_amount' => 0,
                    'balance_amount' => $lpc,
                    'status_id' => InvoiceStatus::NOT_PAID->value, //Not Paid
                    'parent_invoice_id' => $invoice->id,
                    'created_by' => Auth::id(),
                ],
                'items' => $invoice_items,
            ];
            // Generate Invoice with Invoice Service
            $inv_number = InvoiceService::create($invoice_data);
            // Update payment
            $inv_payment = PaymentService::create([
                'invoice_id' => $inv_number['invoice_id'], //InvoiceID
                'payment_date' => Carbon::now()->toDateString(),
                'payment_type_id' => PaymentType::ONLINE->value,
                'transaction_id' => $transaction_details['bank_ref'],
                'pay_transaction_id' => $id,
                'amount' => $lpc,
                'notes' => $transaction_details['notes'],
                'status_id' => PaymentStatus::COMPLETED->value,
            ]);
        }
        // response
        if($payment->id) {
            // response
            return [
                'status' => (int) true,
                'message' => "Payment updated successfully",
            ];
        }else {
            return [
                'status' => (int) false,
                'message' => 'Error in updating Payment',
            ];
        }
    }
    /**
     * Payment Reversal
     * @param $id
     */
    public function reverseGasInvoicePayment(int $id, array $transaction_details)
    {
        // fetch Payment record
        $payments = InvoicePayment::where('pay_transaction_id', $id)->where('status_id', PaymentStatus::COMPLETED->value)->orderBy('id', 'desc')->get();
        $flag = (int) true;
        if($payments->count() > 0) {
            // using foreach loop
            foreach($payments as $payment) {
                $response = PaymentService::reversal($payment, $transaction_details['notes']);
                if(!$response) {
                    $flag = (int) false;
                    break;
                }
            }
            // Call Payment Service - Reversal 
            if($flag) {
                return [
                    'status' => (int) true,
                    'message' => "Payment reversed successfully",
                ];
            }else {
                return [
                    'status' => (int) false,
                    'message' => "Some payments not found",
                ];
            }
        }
        return [
            'status' => (int) false,
            'message' => "Payment not reversed", 
        ];
    }
    /**
     * Payment Success
     * @param $id
     */
    public function processInvoicePayment(int $id, array $transaction_details)
    {
        // Call Payment service
        $payment = PaymentService::create([
            'invoice_id' => $transaction_details['invoice_id'] ?? null,
            'payment_date' => Carbon::now()->toDateString(),
            'payment_type_id' => PaymentType::ONLINE->value,
            'transaction_id' => $transaction_details['bank_ref'],
            'pay_transaction_id' => $id,
            'amount' => $transaction_details['amount'],
            'status_id' => PaymentStatus::COMPLETED->value,
            'notes' => $transaction_details['notes'],
            'created_by' => Auth::id(),
        ]);
        if($payment->id) {
            // response
            return [
                'status' => (int) true,
                'message' => "Payment updated successfully",
            ];
        }else {
            return [
                'status' => (int) false,
                'message' => 'Error in updating Payment',
            ];
        }
    }
    /**
     * Payment Reversal
     * @param $id
     */
    public function reverseInvoicePayment(int $id, array $transaction_details)
    {
        // fetch Payment record
        $payment = InvoicePayment::where('pay_transaction_id', $id)->orderBy('id', 'desc')->first();
        // Call Payment Service - Reversal 
        $response = PaymentService::reversal($payment, $transaction_details['notes']);
        if($response == 1) {
            return [
                'status' => (int) true,
                'message' => "Payment reversed successfully",
            ];
        }else {
            return [
                'status' => (int) false,
                'message' => "Payment not reversed due to invalid payment",
            ];
        }
    }

    /**
     * Recharge Success
     * @param array $transactiondata, int $id
     */
    public function processRecharge(array $transaction_data, int $id)
    {
        // Call Recharge Service
        $add_recharge = RechargeService::create($transaction_data, [
            'consumer_id' => $transaction_data['consumer_id'],
            'recharge_date' => Carbon::now()->toDateString(),
            'amount' => $transaction_data['amount'],
            'balance' => 0,
            'payment_type_id' => PaymentType::ONLINE->value,
            'transaction_id' => $id,
            'status_id' => PaymentStatus::COMPLETED->value,
            'created_by' => Auth::id(),
        ]);
        // response
        if($add_recharge['status'] == 1) {
            return [
                'status' => (int) true,
                'message' => $add_recharge['message'],
            ];
        }else {
            return [
                'status' => (int) false,
                'message' => $add_recharge['message'],
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
        $response = RechargeService::cancel($transaction_data, $id);
        // Response
        if($response['status'] == 1) {
            return [
                'status' => (int) true,
                'message' => $response['message'],
            ];
        }else {
            return [
                'status' => (int) false,
                'message' => $response['message'],
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
            'status' => (int) true,
            'message' => 'SD payment Module under progress',
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
            'status' => (int) true,
            'message' => 'SD payment module under progress',
        ];
    }
}