<?php
 namespace App\Http\Controllers\Payments;

use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SegmentType;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
 use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\Ledger;
use App\Models\Master\PaymentType;
use App\Services\InvoiceService;
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
        $lpc_applied = $bill->childInvoices->contains('type_id', 3);

        return view('payments.gas-invoices.create',[
            'bill' => $bill,
            'payment_types' => $payment_types,
            'lpc_applied' => $lpc_applied
        ]);
    }

    /**
     * To Store the payment of invoice
     * 
     */
    public function store(Request $request)
    {
        // 1. data validation
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'amount' => ['required', 'numeric', 'gt:0','max:' . round(($request->invoice_balance + 10),2)]
            ]);
        $gas_invoice = BillInvoice::find($request->invoice_id);
        // 2. check if LPC is applicable.
        if ($request->lpc_applicable) {
            // Late fee calculation.
            // Invoice Item
            if($gas_invoice->consumer->segment_id == SegmentType::DOMESTIC->value) {
                $inv_item = InvoiceItem::DLPC->value;
            }else if($gas_invoice->consumer->segment_id == SegmentType::COMMERCIAL->value) {
                $inv_item = InvoiceItem::CLPC->value;
            }else {
                $inv_item = InvoiceItem::ILPC->value;
            }
            $late_fee = $request->late_fee;
            $tax_value = 18;
            $basic_amount = round(($late_fee * (100 / (100 + $tax_value))),2);
            $tax_amount = round(($late_fee - $basic_amount),2);
            // Invoice items array preperation.
            $invoice_items[] = [
                'item_id' => $inv_item,
                'quantity' => 1,
                'unit_price' => $basic_amount,
                'total_price' => $basic_amount,
            ];
            // Invoice array preperation for invoice service.
            $invoice_data = [
                'config' => [
                    'state_id' => $gas_invoice->consumer->ga->state_id,
                    'tax_id' => TaxType::GST->value,
                ],
                'headers' => [
                    'type_id' => InvoiceType::LATE_PAYMENT_CHARGES->value,
                    'consumer_id' => $gas_invoice->consumer_id,
                    'invoice_date' => date('Y-m-d'),
                    'base_amount' => $basic_amount,
                    'taxable_amount' => $basic_amount,
                    'tax_id' => TaxType::GST->value,
                    'tax_value' => $tax_value,
                    'tax_amount' => $tax_amount,
                    'total_amount' => $late_fee,
                    'payable_amount' => $late_fee,
                    'balance_amount' => $late_fee,
                    'due_date' => date('Y-m-d'),
                    'status_id' => InvoiceStatus::NOT_PAID->value, //2 =  Unpaid
                    'parent_invoice_id' => $gas_invoice->id,
                    'created_by' => Auth::id(),
                ],
                'items' => $invoice_items,
            ];
            // Generate Invoice with Invoice Service
            $inv_number = InvoiceService::create($invoice_data);
        }
        // 3. Parent invoice details
        $parentInvoice = BillInvoice::with('childInvoices')
            ->findOrFail($request->invoice_id);
        // 4. Connected invoices
        $invoices = $parentInvoice->childInvoices
            ->sortBy('invoice_date')
            ->values();
        // 5. Merge connected invoices + parent (parent last)
        $invoices->push($parentInvoice);
        $remainingAmount = $request->amount;
        $lastInvoice = null;

        foreach ($invoices as $invoice) {
            if ($remainingAmount <= 0) {
                break;
            }
            if ($invoice->balance_amount <= 0) {
                continue;
            }
            $payAmount  = ($invoice->id === $parentInvoice->id) ? $remainingAmount : min(round($invoice->balance_amount, 2), $remainingAmount);
            $newBalance = round($invoice->balance_amount - $payAmount, 2);
            $newPaid    = round($invoice->paid_amount + $payAmount, 2);

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

            // Determine invoice status
            if ($newBalance <= 0) {
                $statusId = InvoiceStatus::PAID->value;
            } elseif ($newPaid > 0) {
                $statusId = InvoiceStatus::PARTIALLY_PAID->value;
            } else {
                $statusId = InvoiceStatus::NOT_PAID->value;
            }
            // Update invoice
            $invoice->update([
                'paid_amount'    => $newPaid,
                'balance_amount' => $newBalance,
                'status_id'      => $statusId, // Paid / Partial
            ]);

            // If invoice is sd emi invoice payment, update the status as paid.
            if($invoice->type_id == InvoiceType::SD_EMI->value && $newBalance == 0)
            {
                ConsumerSdPayment::where('invoice_id', $invoice->id)->update(['status_id' => 1]);
            }
            $remainingAmount = round($remainingAmount - $payAmount, 2);
            $lastInvoice = $invoice;
        }

        // $paisaTolerance = 1.00;
        // if ($lastInvoice) {
        //     $leftover = $lastInvoice->balance_amount > 0
        //         ? round($lastInvoice->balance_amount, 2)   // +ve underpaid
        //         : round(-$remainingAmount, 2);              // -ve overpaid

        //     // Only apply PAID status if within tolerance
        //     if ($leftover != 0 && abs($leftover) <= $paisaTolerance) {
        //         InvoicePayment::where('invoice_id', $lastInvoice->id)
        //             ->latest()
        //             ->first()
        //             ->update(['balance' => $leftover]);

        //         $parentInvoice->update([
        //             'balance_amount' => $leftover,
        //             'status_id'      => 1, // PAID only within tolerance
        //         ]);
        //     }
        // }

        return response()->json(['success' => 'Invoice payment inserted successfully']);
    }
}