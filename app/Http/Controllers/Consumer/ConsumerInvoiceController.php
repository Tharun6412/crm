<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use Illuminate\Http\Request;

class ConsumerInvoiceController extends Controller
{
    /**
     * index
     * 
     * List of gas bills and invoices for the selected consuer
     * 
     * @param Int $id consumer_id
     * @param Int $type Invoice type
     */
    public function index(Request $request, $id, $type = InvoiceType::GAS_BILL->value)
    {
        // Get invoices with type = 1
        $invoices = BillInvoice::where('consumer_id', $id)
            ->when(($type == InvoiceType::GAS_BILL->value), function($q) use($type) {
                $q->where('type_id', $type);
            })
            ->when(($type != 1), function ($q) {
                $q->whereNot('type_id', 1);
            })
            ->orderBy('invoice_date', 'desc')->paginate(20);

        // Render output
        if($type == InvoiceType::GAS_BILL->value)
            return view('consumers.consumers.show-bills', ['invoices' => $invoices]);
        else
            return view('consumers.consumers.show-invoices', ['invoices' => $invoices]);
    }
}