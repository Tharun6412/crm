<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\Constants;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\PaymentType;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Adjust pagination
     */
    use ApiResponse;

    /**
     * Invoices List
     * @method GET
     * @param $consumer_id
     */
    public function list(Request $request, $consumer_id)
    {
        // Get Invoices list
        $invoices_q = BillInvoice::with([
            'invoiceType:id,name',
            'status:id,name',
        ])
        ->select('id', 'type_id', 'invoice_number', 'invoice_date', 'total_amount', 'payable_amount', 'paid_amount', 'balance_amount', 'status_id', 'due_date', 'created_at')
        ->where('consumer_id', $consumer_id)
        ->whereNotIn('type_id', [InvoiceType::GAS_BILL->value])
        ->paginate(10);

        $invoices = $this->apiPagination($invoices_q);
        return response()->json(['invoices' => $invoices], 200);
    }

    /**
     * Invoice details
     * @param $invoice_id
     * @method GET
     */
    public function viewInvoice(Request $request, $id)
    {
        // Find Invoice
        $invoice = BillInvoice::with([
            'invoiceType:id,name',
            'status:id,name',
            'tax:id,name',
            'items:id,invoice_id,item_id,description,quantity,unit_price,total_price',
            'items.item:id,name',
            'creditNotes',
        ])->find($id);
        // Abort if Invoice not found
        if (! $invoice) {
            return response()->json(['error' => 'Invoice not found'], 422);
        }
        // Payment Types
        $payment_types = PaymentType::select('id', 'name', 'status')->get();
        // Get Invoice details
        return response()->json([
            'invoice' => $invoice,
            'payment_types' => $payment_types,
        ], 200);
    }

    /**
     * Gas Bill list
     * @method GET
     * @param $consumer_id
     */
    public function gasBills(Request $request, $consumer_id)
    {
        // Get Gas Invoices list
        $invoices_q = BillInvoice::with([
            'invoiceType:id,name',
            'status:id,name',
        ])
        ->select('id', 'type_id', 'invoice_number', 'invoice_date', 'total_amount', 'payable_amount', 'paid_amount', 'balance_amount', 'status_id', 'due_date', 'created_at')
        ->where('consumer_id', $consumer_id)
        ->where('type_id', InvoiceType::GAS_BILL->value)
        ->paginate(10);

        $invoices = $this->apiPagination($invoices_q);
        return response()->json(['invoices' => $invoices], 200);
    }

    /**
     * View Gas Bill
     * @method GET
     * @param $invoice_id
     */
    public function viewGasBill(Request $request, $id)
    {
        // Find Invoice
        $invoice = BillInvoice::with([
            'invoiceType:id,name',
            'status:id,name',
            'tax:id,name',
            'consumption:id,invoice_id,meter_id,date_from,date_to,prev_reading,curr_reading,net_consumption,unit_price',
            'childInvoices',
            'creditNotes',
        ])->where('type_id', InvoiceType::GAS_BILL->value)->find($id);
        // Abort if Invoice not found
        if (! $invoice) {
            return response()->json(['error' => 'Invoice not found'], 422);
        }
        // Check Invoice Due Date with Current Date
        $late_fee = 0;
        if(Carbon::now()->toDateString() > $invoice->due_date) {
            // Check Late Fee invoice
            if($invoice->childInvoices->contains('type_id', 3)) {
                $late_fee = 0;
            }else {
                switch($invoice->consumer->segment_id) {
                    case 1:
                        $late_fee = Constants::DPNG_LPC->value;break;
                    case 2:
                        $late_fee = Constants::CPNG_LPC->value;break;
                    case 3:
                        $late_fee = Constants::IPNG_LPC->value;break;
                    default:
                        $late_fee = 0;
                }
            }
        }
        // Payment Types
        $payment_types = PaymentType::select('id', 'name', 'status')->get();
        // Response Data
        return response()->json([
            'invoice' => $invoice,
            'late_fee' => $late_fee,
            'payment_types' => $payment_types,
        ], 200);
    }
}