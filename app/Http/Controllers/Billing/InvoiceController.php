<?php
namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Master\BillInvoiceType;

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
        $consumer = Consumer::find($id);
        $invoice_types = BillInvoiceType::all();

        return view('consumers.invoices.create', ['consumer' => $consumer, 'invoice_types' => $invoice_types]);
    }
}