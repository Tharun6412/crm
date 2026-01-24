<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Traits\ApiResponse;
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
     */
    public function list(Request $request)
    {
        if(empty($request->key) || !preg_match('/^[a-zA-Z0-9]+$/', $request->key)) {
            return response()->json(['message' => 'Please select consumer or Invoice number'], 422);
        }
        // Get Invoices list
        $invoices_q = BillInvoice::where(function ($query) use ($request) {
                $query->whereHas('consumer', function ($q) use ($request) {
                    $q->where('crn', 'like', '%' . trim($request->key) . '%');
                })
                ->orWhere('invoice_number', 'like', '%' . trim($request->key) . '%');
            })
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
        // Get Invoice details
        return response()->json([
            'invoice' => $invoice,
        ], 200);
    }

    /**
     * Gas Bill list
     * @method GET
     */
    public function gasBills(Request $request)
    {
        // Gas Invoice
        if(empty($request->key) || !preg_match('/^[a-zA-Z0-9]+$/', $request->key)) {
            return response()->json(['message' => 'Please select consumer or Invoice number'], 422);
        }
        // Get Invoices list
        $invoices_q = BillInvoice::where(function ($query) use ($request) {
                $query->whereHas('consumer', function ($q) use ($request) {
                    $q->where('crn', 'like', '%' . trim($request->key) . '%');
                })
                ->orWhere('invoice_number', 'like', '%' . trim($request->key) . '%');
            })
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
        // Get Invoice details
        return response()->json([
            'invoice' => $invoice,
        ], 200);
    }
}