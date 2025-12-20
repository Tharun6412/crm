<?php

namespace App\Services;

use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillStateInvoiceCounter;
use Illuminate\Support\Facades\DB;

class InvoiceGeneration 
{
    /**
     * Service Invoices
     */
    public static function serviceInvoiceGenerate(array $invoice_details, $inv_number_details)
    {
        // Service Invoice Generation
        $add_service_invoice = BillInvoice::create($invoice_details);
        // Invoice Number Generation
        $inv_number = self::invoiceNumberGenerate($inv_number_details);
        BillInvoice::where('id', $add_service_invoice->id)->update(['invoice_number' => $inv_number]);
        return $add_service_invoice->id;    
    }

    /**
     * Invoice Number Generation
     * @params state_id, state_code, inv_type
     */
    public static function invoiceNumberGenerate($inv_number_details) : string
    {
        $inv_type_data = BillStateInvoiceCounter::firstOrNew(['state_id' => $inv_number_details['state_id'], 'invoice_type' => $inv_number_details['inv_type']]);
        // Values to be inserted
        $inv_code = ($inv_number_details['inv_type'] == "1") ? "G" : "V";
        $inv_type_data->invoice_code = $inv_number_details['state_code'].$inv_code;
        $inv_type_data->count = ($inv_type_data->count ?? 0) + 1;
        $inv_type_data->save(); //Insert or Update
        // Number Generate
        $inv_number = $inv_type_data->invoice_code.str_pad($inv_type_data->count, 9, "0", STR_PAD_LEFT);
        return $inv_number;
    }
}
