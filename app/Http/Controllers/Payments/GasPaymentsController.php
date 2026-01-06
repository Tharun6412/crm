<?php
 namespace App\Http\Controllers\Payments;

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
/**
 * This is the payments controller for Gas Invoices.
 */
class GasPaymentsController extends Controller
{
    /**
     * List of Gas Invoice Payments
     * 
     */
    public function index()
    {
        echo "List of Gas Invoice Payments";
    }

    /**
     * This create function is to load the modal for payment update against an invoice.
     * @param INT $id Invoice id
     */
    public function create(Request $request, $id)
    {
        $bill = BillInvoice::find($id);
        $payment_types = PaymentType::all();

        return view('payments.gas-invoices.create',[
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

        $parentInvoice = BillInvoice::with('childInvoices')
            ->findOrFail($request->invoice_id);

        // Merge child invoices + parent (parent last)
        $invoices = $parentInvoice->childInvoices
            ->sortBy('invoice_date')
            ->values();

        $invoices->push($parentInvoice);
        $remainingAmount = $request->amount;

        foreach ($invoices as $invoice) {
            if ($remainingAmount <= 0) {
                break;
            }
            if ($invoice->balance_amount <= 0) {
                continue;
            }
            $payAmount = min($invoice->balance_amount, $remainingAmount);
            $newBalance = $invoice->balance_amount - $payAmount;
            $newPaid    = $invoice->paid_amount + $payAmount;

            // Create payment record for THIS invoice
            PaymentService::create([
                'invoice_id'      => $invoice->id,
                'payment_date'    => date('Y-m-d'),
                'payment_type_id' => $request->payment_type,
                'transaction_id'  => $request->transaction_no,
                'amount'          => $payAmount,
                'balance'         => $newBalance,
                'status_id'       => 1,
                'notes'           => $request->notes,
                'created_by'      => Auth::id(),
            ]);

            // Update invoice
            $invoice->update([
                'paid_amount'    => $newPaid,
                'balance_amount' => $newBalance,
                'status_id'      => ($newBalance == 0) ? 1 : 3, // Paid / Partial
            ]);
            $remainingAmount -= $payAmount;
        }
        return response()->json(['success' => 'Invoice payment inserted successfully']);
    }
}