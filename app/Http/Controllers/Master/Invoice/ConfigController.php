<?php

namespace App\Http\Controllers\Master\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice\InvoiceCounter;
use App\Models\Master\State;
use App\Models\Master\Tax;
use App\Models\Master\TaxGroup;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get Invoice Counters
        $taxes = Tax::all();
        $invoiceNumbering = InvoiceCounter::all();

        // Render output
        return view('master.invoice.config.list', [
            'invoice_numbering' => $invoiceNumbering,
            'taxes' => $taxes,
        ]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Get states
        $states = State::all();
        $taxGroups = TaxGroup::all();

        // Render Output
        return view('master.invoice.config.create', [
            'states' => $states,
            'tax_groups' => $taxGroups,
        ]);
    }

    /**
     * Save
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'state_id' => 'required',
            'tax_group_id' => 'required',
            'invoice_code' => 'required',
        ]);

        // Insert
        $new_inv_code = InvoiceCounter::create([
            'state_id' => $request->state_id,
            'tax_group_id' => $request->tax_group_id,
            'invoice_code' => $request->invoice_code,
            'count' => 0,
        ]);

        // Response
        return response()->json(['success' => 'Invoice counter created successfully!']);
    }
}