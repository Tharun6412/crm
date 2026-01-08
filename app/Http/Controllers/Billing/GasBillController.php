<?php

namespace App\Http\Controllers\Billing;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use Illuminate\Support\Facades\App;

class GasBillController extends Controller
{
    /**
     * Show
     * 
     * Display gasbill
     * @param Int InvoiceId
     */
    public function show($id)
    {
        // Get Gas bill only
        $invoice = BillInvoice::where(['id' => $id, 'type_id' => InvoiceType::GAS_BILL->value])->first();

        // Check gas bill
        if(!$invoice)
            abort('403', 'Invalid invoice');

        // Set Locale for regional language
        App::setLocale($invoice->consumer->state->lang_code ?? 'tel');
        // Render output
        return view('billing.gas-bill.show', ['invoice' => $invoice]);
    }
}