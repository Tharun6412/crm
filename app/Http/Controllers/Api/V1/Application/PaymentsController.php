<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Payments Controller
 */
class PaymentsController extends Controller
{
    /**
     * Adjust pagination
     */
    use ApiResponse;
    
    /**
     * List of invoices.
     * @param $request  -search key (invoice number or crn)
     */
    public function list(Request $request)
    {
        // 1. checking the search parameter. 
        if(!$request->key) {
            return response()->json(['error' => 'Search Key is required'], 422);
        }
        // Get Invoices
        $invoices_q = BillInvoice::with([
            'invoiceType:id,name',
            'status:id,name',
        ])->whereHas('consumer', function($q) use($request) {
            $q->where('crn', 'like', '%'.$request->key.'%');
        })->orWhere('invoice_number', 'like', '%'.$request->key.'%')->paginate(20);
        $invoices = $this->apiPagination($invoices_q);

        return response()->json(['invoices' => $invoices]);
    }

    // commented [not used yet.]
    /*public function edit(Request $request, $id)
    {
        // 1. check if the id is passed or not.
        if(!$id) {
            return response()->json(['error' => 'Invalid invoice Id'], 422);
        }
        // 2. Fetching of gas invoice 
        $invoice = BillInvoice::with([
            'consumer:id,crn,fname,lname',
            'invoiceType:id,name',
            'status:id,name',
            'tax:id,name',
            'consumption:id,invoice_id,meter_id,date_from,date_to,prev_reading,curr_reading,net_consumption,unit_price',
            'consumption.meter:id,meter_no,meter_serial_no',
            'childInvoices',
            'childInvoices.invoiceType:id,name',
            'creditNotes',
        ])->where('id', $id)->get();
        $payment_types = PaymentType::all();
        // 3. check for invoice details.
        if(!$invoice) {
            return response()->json(['error' => 'No invoice details found'], 200);
        }
        return response()->json(['invoice' => $invoice, 'payment_types' => $payment_types]);
    }*/

    /**
     * Payment submit for the invoice and dependent invoices.
     * 
     */
    public function update(Request $request)
    {
        // 1. data validation
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'amount' => ['required', 'numeric', 'gt:0', 'min:' . $request->invoice_balance, 'max:' . $request->invoice_balance]
            ]);
        $invoice = BillInvoice::find($request->invoice_id);
        // 2. check if LPC is applicable.
        if ($request->late_fee > 0) {
            // Late fee calculation.
            $late_fee = $request->late_fee;
            $tax_value = 18;
            $basic_amount = round(($late_fee * (100 / (100 + $tax_value))),2);
            $tax_amount = round(($late_fee - $basic_amount),2);
            // Invoice items array preperation.
            $invoice_items[] = [
                'item_id' => 4,
                'quantity' => 1,
                'unit_price' => $basic_amount,
                'total_price' => $basic_amount,
            ];
            // Invoice array preperation for invoice service.
            $invoice_data = [
                'config' => [
                    'state_id' => $invoice->consumer->ga->state_id,
                    'tax_id' => TaxType::GST->value,
                ],
                'headers' => [
                    'type_id' => InvoiceType::LATE_PAYMENT_CHARGES->value,
                    'consumer_id' => $invoice->consumer_id,
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
                    'parent_invoice_id' => $invoice->id,
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
                'status_id'       => PaymentStatus::COMPLETED->value,
                'notes'           => $request->notes,
                'created_by'      => Auth::id(),
            ]);

            // Update invoice
            $invoice->update([
                'paid_amount'    => $newPaid,
                'balance_amount' => $newBalance,
                'status_id'      => ($newBalance == 0) ? InvoiceStatus::PAID->value : InvoiceStatus::PARTIALLY_PAID->value, // Paid / Partial
            ]);
            $remainingAmount -= $payAmount;
        }
        return response()->json(['success' => 'Invoice payment updated successfully']);
    }
}