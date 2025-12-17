<?php

namespace App\Http\Controllers\Master\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Master\BillInvoiceType;

class InvoiceTypesController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get details
        $invoice_types = BillInvoiceType::all();

        // Render output
        return view('master.invoice.types.list', ['invoice_types' => $invoice_types]);
    }
}