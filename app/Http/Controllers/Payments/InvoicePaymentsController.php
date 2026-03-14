<?php

namespace App\Http\Controllers\Payments;

use App\Enums\Constants;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Models\Master\PaymentType;
use App\Services\LedgerService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InvoicePaymentsController extends Controller
{
    /**
     * List of Gas Invoice Payments
     * 
     */
    public function index()
    {
        echo "List of Invoice Payments";
    }

    /**
     * This create function is to load the modal for payment update against an invoice.
     * @param INT $id Invoice id
     */
    public function create(Request $request, $id)
    {
        $bill = BillInvoice::find($id);
        $payment_types = PaymentType::all();
        // Generate LPC if Due Date less than current date
        $late_fee = '0';
        if($bill->type_id == InvoiceType::GAS_BILL->value) {
            if(Carbon::now()->toDateString() > $bill->due_date) {
                // Check Late Fee invoice
                if($bill->childInvoices->contains('type_id', 3)) {
                    $late_fee = '0';
                }else {
                    switch($bill->consumer->segment_id) {
                        case 1:
                            $late_fee = Constants::DPNG_LPC->value;break;
                        case 2:
                            $late_fee = Constants::CPNG_LPC->value;break;
                        case 3:
                            $late_fee = Constants::IPNG_LPC->value;break;
                        default:
                            $late_fee = '0';
                    }
                }
            }
        }
        return view('payments.invoices.create',[
            'bill' => $bill,
            'payment_types' => $payment_types,
            'late_fee' => $late_fee,
        ]);
    }

    /**
     * To Store the payment of invoice
     * 
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'amount' => ['required', 'numeric', 'gt:0', 'min:' . $request->invoice_balance, 'max:' . $request->invoice_balance]
        ]);

        // Prepare data for Payment service
        $payment_data = PaymentService::create([
            'invoice_id' => $request->invoice_id,
            'payment_date' => date('Y-m-d'),
            'payment_type_id' => $request->payment_type,
            'transaction_id' => $request->transaction_no,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'status_id' => 1,
        ]);
        /*
        $rem_balance = ($request->invoice_balance - $request->amount);
        $inv_payment_status = ($rem_balance == 0) ? 1 : 3; 
        $till_paid_amount = ($request->till_paid_amount + $request->amount);
        $payment_ar = [
            'invoice_id' => $request->invoice_id,
            'payment_date' => date('Y-m-d'),
            'payment_type_id' => $request->payment_type,
            'transaction_id' => $request->transaction_no,
            'amount' => $request->amount,
            'balance' => $rem_balance,
            'status_id' => 1,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ];

        $insert = InvoicePayment::create($payment_ar);
        if($insert) {
            // Add to Ledger 
            $ledger_data[] = [
                'model' => $insert,
                'consumer_id' => $insert->invoice->consumer_id,
                'description' => "Bill Payment: Invoice Generated with Invoice No.",
                'credit' => $insert->amount,
                'debit' => NULL,
                'balance' => $insert->amount, 
            ];
            // Call Ledger Service
            LedgerService::create($ledger_data, 'credit');
            $inv_ar = [
                'id' => $request->invoice_id,
                'status_id' => $inv_payment_status,
                'paid_amount' => $till_paid_amount,
                'balance_amount' => $rem_balance,
            ];
            $inv_insert = BillInvoice::where('id', $request->invoice_id)->update($inv_ar);
        }*/
        return response()->json(['success' => 'Invoice payment updated successfully']);
    }

    /**
     * Show Payments By Consumer ID
     * @param int consumer_id
     */
    public function show(Request $request, $id)
    {
        $payments = InvoicePayment::whereHas('invoice', function($q) use($id) {
            $q->where(['consumer_id' => $id, 'type_id' => InvoiceType::GAS_BILL->value]);
        })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('consumers.consumers.show-payments', [
            'payments' => $payments,
        ]);
    }
}