<?php

namespace App\Http\Controllers\Master\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Master\BillInvoiceItem;
use App\Models\Master\BillInvoiceItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InvoiceItemsController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get all invoice items
        $items = BillInvoiceItem::when($request->has('key'), function($q) use($request) {
                $q->whereAny(['code', 'name', 'hsn', 'price'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('item_types'), function ($q) use($request) {
                $q->whereIn('type_id', $request->item_types);
            })
            ->paginate(50)->withQueryString();

        // Render output
        if($request->ajax())
            return view('master.invoice.items.list-body', ['items' => $items]);
        else
            return view('master.invoice.items.list', ['items' => $items]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Get
        $item_types = BillInvoiceItemType::all();

        // Render output
        return view('master.invoice.items.create', ['item_types' => $item_types]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'type_id' => 'required',
            'code' => 'required|unique:App\Models\Master\BillInvoiceItem,code',
            'name' => 'required|max:224',
            'hsn' => 'required',
            'basic' => 'required|numeric',
            'tax' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        // Insert into Invoice items
        $insert_item = BillInvoiceItem::create([
            'type_id' => $request->type_id,
            'code' => $request->code,
            'name' => $request->name,
            'hsn' => $request->hsn,
            'basic' => $request->basic,
            'tax_value' => $request->tax,
            'price' => $request->price,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Item created successfully!']);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get details
        $item = BillInvoiceItem::findOrFail($id);

        // Render output
        return view('master.invoice.items.show', ['item' => $item]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get item types
        $item_types = BillInvoiceItemType::all();
        $item = BillInvoiceItem::findOrFail($id);

        // Render output
        return view('master.invoice.items.edit', [
            'item_types' => $item_types,
            'item' => $item,
        ]);
    }

    /**
     * Update invoice
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'type_id' => 'required',
            'code' => ['required', Rule::unique(BillInvoiceItem::class)->ignore($id)],
            'name' => 'required|max:224',
            'hsn' => 'required',
            'basic' => 'required|numeric',
            'tax' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        // Update Invoice items
        $insert_item = BillInvoiceItem::where('id', $id)->update([
            'type_id' => $request->type_id,
            'code' => $request->code,
            'name' => $request->name,
            'hsn' => $request->hsn,
            'basic' => $request->basic,
            'tax_value' => $request->tax,
            'price' => $request->price,
            'updated_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Item updated successfully!']);
    }
}