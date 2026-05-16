<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\SegmentType;
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
        $ga_ids = $request->user()->ga()->pluck('ga_id')->toArray();
        $invoices_q = BillInvoice::with([
                'consumer:id,fname,lname,crn,ga_id,district_id,status_id,segment_id',
                'consumer.ga:id,name',
                'consumer.district:id,name',
                'consumer.status:id,name',
                'consumer.segment:id,name',
                'invoiceType:id,name',
                'status:id,name',
            ])
            ->where(function($query) use($request, $ga_ids) {
                $query->where('invoice_number', 'like', '%' . $request->key . '%')
                    // Search by CRN or GA name via consumer relation
                    ->orWhereHas('consumer', function($q) use($request, $ga_ids) {
                        $q->where(function($q1) use($request) {
                            $q1->where('crn', 'like', '%' . $request->key . '%')
                            ->orWhereHas('ga', function($q2) use($request) {
                                    $q2->where('name', 'like', '%' . $request->key . '%');
                            });
                        });
                        if(!empty($ga_ids) AND ((!isApiAdmin() AND !isApiSuperAdmin() AND !isApiFullAccess()))) {
                            $q->whereIn('ga_id', $ga_ids);
                        }
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        $invoices = $this->apiPagination($invoices_q);
        // Response
        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Payment submit for the invoice and dependent invoices.
     * 
     */
    public function update(Request $request)
    {
        $invoice = BillInvoice::find($request->invoice_id);
        $total_payable = round($invoice->balance_amount + $invoice->childInvoices->sum('balance_amount') + $request->late_fee, 2);
        // 1. data validation
        $request->validate([
            'invoice_id' => 'required',
            'payment_type' => 'required',
            'transaction_no' => 'required',
            'amount' => ['required', 'numeric', 'gt:0']
            ]);
        // 2. check if LPC is applicable.
        if ($request->late_fee > 0) {
            // Invoice Item
            if($invoice->consumer->segment_id == SegmentType::DOMESTIC->value) {
                $inv_item = InvoiceItem::DLPC->value;
            }else if($invoice->consumer->segment_id == SegmentType::COMMERCIAL->value) {
                $inv_item = InvoiceItem::CLPC->value;
            }else {
                $inv_item = InvoiceItem::ILPC->value;
            }
            // Late fee calculation.
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
            // Child Invoice not accepted for partial Payments
            if($invoice->id !== $parentInvoice->id) {
                if($remainingAmount < $invoice->balance_amount){
                    continue;
                }
                $payAmount = $invoice->balance_amount;
            }else {
                // Parent Invoice can accept Partial Amount
                $payAmount = $remainingAmount;
            }
            // $payAmount = min($invoice->balance_amount, $remainingAmount);
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
            // $invoice->update([
            //     'paid_amount'    => $newPaid,
            //     'balance_amount' => $newBalance,
            //     'status_id'      => ($newBalance == 0) ? InvoiceStatus::PAID->value : InvoiceStatus::PARTIALLY_PAID->value, // Paid / Partial
            // ]);
            $remainingAmount -= $payAmount;
        }
        return response()->json(['success' => 'Invoice payment updated successfully']);
    }
}