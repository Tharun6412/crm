<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\BillInvoiceItem;
use App\Models\Master\BillInvoiceItemType;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\Tax;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Index function for the list of invoices
     */
    public function index()
    {
        return view('billing.billing.list');
    }

    /**
     * Create Invoice
     * 
     * @param $id Consumer Id
     */
    public function create($id)
    {
        // Get consumer
        $consumer = Consumer::whereNot('status_id', 1)->find($id);
        
        // Abort if consumer not found
        if (! $consumer) {
            abort(403, 'Consumer not found');
        }

        // Get required data for invoice creation
        $invoice_types = BillInvoiceType::all();
        $item_types = BillInvoiceItemType::all();

        // Render output
        return view('billing.invoices.create', [
            'consumer' => $consumer, 
            'invoice_types' => $invoice_types,
            'item_types' => $item_types,
            'invoice_items' => [],
        ]);
    }

    /**
     * Create invoice body with items
     */
    public function createBody(Request $request)
    {
        // Define
        $items = collect();
        $inv_items = [];
        // Check items in session
        if($request->session()->has('inv_items')) {
            $inv_items = $request->session()->get('inv_items');
            // Get Items list from database
            $items = BillInvoiceItem::whereIn('id', array_keys($inv_items))->get();
        }

        // Get additional data
        $tax_types = Tax::all();

        // Render output
        return view('billing.invoices.create-body', [
            'items' => $items,
            'inv_items' => $inv_items,
            'tax_types' => $tax_types,
        ]);
    }

    /**
     * Add item to invoice
     */
    public function addItem(Request $request)
    {
        // Validation
        $request->validate([
            'type_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|numeric|gt:0',
        ]);

        // Create session
        $inv_items = $request->session()->get('inv_items');
        if(isset($inv_items[$request->item_id])) {
            $inv_items[$request->item_id]['qty'] += $request->quantity;
        }
        else {
            $inv_items[$request->item_id] = [
                'qty' => $request->quantity
            ];
        }
        // Update the session
        $request->session()->put('inv_items', $inv_items);

        // Response 
        return response()->json(['success' => 'Item added to invoice!']);
    }

    /**
     * Remove item from session
     */
    public function removeItem(Request $request)
    {
        // Get session data
        $inv_items = $request->session()->get('inv_items');
        // Remove item from session
        unset($inv_items[$request->item_id]);
        // Update the session
        $request->session()->put('inv_items', $inv_items);

        // Response
         return response()->json(['msg' => 'Item removed!']);
    }

    /**
     * Fetch the invoice items based on invoice item type
     */
    public function typeItems(Request $request)
    {
        // Get invoice items list
        $item_types = BillInvoiceItem::where('type_id', $request->type_id)->get();
        
        // Response
        return response()->json(['items' => $item_types]);
    }

    /**
     * Save invoice
     * @param id consumer_id
     */
    public function store(Request $request, $id)
    {
        // Validation
        $request->validate([
            'invoice_type' => 'required',
            'tax_type_id' => 'required',
            'tax_value' => 'required|numeric',
        ]);

        // Prepate invoice data
        $inv_items = $request->session()->get('inv_items');
        $items = BillInvoiceItem::whereIn('id', array_keys($inv_items))->get();
        $invoice_items = [];
        $items_total = 0;
        foreach($items as $item) {
            $item_total = ($item->basic * $inv_items[$item->id]['qty']);
            $items_total += $item_total;
            $invoice_items[] = [
                'item_id' => $item->id,
                'quantity' => $inv_items[$item->id]['qty'],
                'unit_price' => $item->basic,
                'total_price' => $item_total,
            ];
        }
        $tax_amount = round($items_total * ($request->tax_value / 100), 2);
        $invoice_total = $items_total + $tax_amount;

        $invoice_data = [
            'config' => [
                'state_id' => 1,
                'tax_id' => $request->tax_type_id,
            ],
            'headers' => [
                'type_id' => $request->invoice_type,
                'consumer_id' => $id,
                'invoice_date' => date('Y-m-d'),
                'base_amount' => $items_total,
                'taxable_amount' => $items_total,
                'tax_id' => $request->tax_type_id,
                'tax_value' => $request->tax_value,
                'tax_amount' => $tax_amount,
                'total_amount' => $invoice_total,
                'due_date' => date('Y-m-d'),
                'status_id' => 2,
            ],
            'items' => $invoice_items,
        ];

        // Generate Invoice with Invoice Service
        $inv_number = InvoiceService::create($invoice_data);

        // Unset session
        $request->session()->forget('inv_items');

        // Response 
        return response()->json(['success' => 'Invoice created successfully with invoice number ' . $inv_number['invoice_number']]);
    }

    /**
     * Show invoice
     * 
     * @param Int Invoice Id
     */
    public function show($id)
    {
        // Get invoice details
        $invoice = BillInvoice::find($id);
        if(!$invoice)
            abort(403, 'Invoice not found');

        // Rendert output
        return view('billing.invoices.show', ['invoice' => $invoice]);
    }
}