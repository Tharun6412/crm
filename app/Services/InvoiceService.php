<?php

namespace App\Services;

use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoiceCounter;
use App\Models\Invoice\InvoiceItem;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Create invoice
     * 
     * @param array $invoice_data
     * $invoice_data = [config, headers , items]
     */
    public static function create($invoice_data)
    {
        // 1. Generate Invoice_no
        $inv_number = self::generateNumber($invoice_data['config']['state_id'], $invoice_data['config']['tax_id']);
        // Push invoice number into invoice data
        $invoice_data['headers']['invoice_number'] = $inv_number;
        // 2. Insert invoice
        $new_invoice = BillInvoice::create($invoice_data['headers']);
        // Push new invoice_id to invoice item
        foreach($invoice_data['items'] as &$item) {
            $item['invoice_id'] = $new_invoice->id;
        }
        // 3. Invoice items
        $new_inv_item = InvoiceItem::insert($invoice_data['items']);

        // Response
        return ['invoice_id' => $new_invoice->id, 'invoice_number' => $inv_number];
    }

    /**
     * Generate invoice number
     * 
     * @param FK $state_id
     * @param FK $tax_id
     */
    public static function generateNumber($state_id, $tax_id)
    {
        // Generate invoice number with state and tax group
        $inv_counter = InvoiceCounter::where('state_id', $state_id)
            ->whereHas('taxGroup.taxes', function($q) use($tax_id) {
                $q->where('mst_taxes.id', $tax_id);
            }
            )->first();

        // Check invoice counter group
        if($inv_counter) {
            // Increment counter
            InvoiceCounter::where('id', $inv_counter->id)->increment('count');
            $counter = $inv_counter->count + 1;
            // Invoice Number
            $inv_number = $inv_counter->invoice_code . str_pad($counter, 9, "0", STR_PAD_LEFT);
        }
        else {
            // Random number generation
            $inv_number = Str::random(12);
        }

        // Return generated invoice number
        return $inv_number;
    }
}