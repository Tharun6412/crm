<?php
 namespace App\Http\Controllers\Payments;

 use App\Http\Controllers\Controller;
 use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Models\Master\PaymentType;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
/**
 * This is the payments controller for Gas Invoices.
 */
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

        return view('payments.invoices.create',[
            'bill' => $bill,
            'payment_types' => $payment_types
        ]);
    }

    /**
     * To Store the payment of invoice
     * 
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
             'amount' => ['required', 'numeric', 'gt:0', 'min:' . $request->invoice_balance, 'max:' . $request->invoice_balance]
        ]);

        // $bill = BillInvoice::find($request->invoice_id);
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
            $payment = InvoicePayment::find($insert->id);
            // Get the Latest Ledger Data
            $ledger = Ledger::where('consumer_id', $payment->invoice->consumer_id)->latest('id')->first();
            $balance = $ledger->balance ? $ledger->balance - $payment->amount : $payment->amount;
            $ledger_data[] = [
                'model' => $payment,
                'consumer_id' => $payment->invoice->consumer_id,
                'description' => $request->notes,
                'credit' => NULL,
                'debit' => $payment->amount,
                'balance' => $balance, 
            ];
            // Call Ledger Service
            LedgerService::create($ledger_data);
            $inv_ar = [
                'id' => $request->invoice_id,
                'status_id' => $inv_payment_status,
                'paid_amount' => $till_paid_amount,
                'balance_amount' => $rem_balance,
            ];
            $inv_insert = BillInvoice::where('id', $request->invoice_id)->update($inv_ar);
        }
        return response()->json(['success' => 'Invoice payment inserted successfully']);
    }
}