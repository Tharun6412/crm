<?php
namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Master\BillInvoiceItem;
use App\Models\Master\BillInvoiceItemType;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\Tax;
use Illuminate\Http\Request;

/**
 *  This is a common controller for all type of invoices (GST/VAT)
 * 
 */
class InvoiceController extends Controller
{
    /**
     * Index function for the list of invoices
     * 
     */
    public function index()
    {
        echo "Invoice generation controller";
    }

    /**
     * create function for the adding of new invoice. 
     * 
     */
    public function create($id)
    {
        $consumer = Consumer::whereNot('status_id',1)->find($id);
        $invoice_item_types = BillInvoiceItemType::all();

        return view('consumers.invoices.create', [
            'consumer' => $consumer, 
            'invoice_item_types' => $invoice_item_types,
            'invoice_items' => [],
        ]);
    }

    /**
     * Fetch the invoice items based on invoice item type
     * 
     */
    public function invoiceItems(Request $request)
    {
        $item_types = BillInvoiceItem::where('type_id', $request->invoice_type)->get();

        return response()->json(['items' => $item_types]);
    }

    /**
     * 
     * Render the Tax form 
     */
    public function renderTax(Request $request)
    {
        $request->validate([
            'item' => 'required',
            'quantity' => 'required|numeric',
        ]);
        $item = BillInvoiceItem::find($request->item);
        $tax_types = Tax::all();

        return view('consumers.invoices.tax-body',['item' => $item, 'tax_types' => $tax_types]);
    }
}