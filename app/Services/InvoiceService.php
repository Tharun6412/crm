<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceCancel;
use App\Models\Invoice\InvoiceCounter;
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\Ledger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Create invoice
     * 
     * @param array $invoice_data
     * $invoice_data = [config, headers, items]
     */
    public static function create($invoice_data)
    {
        // 1. Generate Invoice_no
        $inv_number = self::generateNumber($invoice_data['config']['state_id'], $invoice_data['config']['tax_id']);
        
        // 2. Insert invoice
        // Push invoice number into invoice data
        $invoice_data['headers']['invoice_number'] = $inv_number;
        $new_invoice = BillInvoice::create($invoice_data['headers']);

        // 3. Invoice items
        // Push new invoice_id to invoice item
        foreach($invoice_data['items'] as &$item) {
            $item['invoice_id'] = $new_invoice->id;
        }
        $new_inv_item = InvoiceItem::insert($invoice_data['items']);
        
        // 4. Add record to ledger
        $ledger_record = LedgerService::create([
            'model' => $new_invoice,
            'consumer_id' => $new_invoice->consumer_id,
            'amount' => ($invoice_data['headers']['total_amount'] ?? 0),
        ], 'dr');

        // Return
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

    /**
     * To Cancel Invoice
     * @param $invoiceId
     */
    public static function cancel(int $id, string $notes)
    {
        // Fetch Invoice Details
        $invoice = BillInvoice::find($id);
        // Add to Ledger Record
        $ledger_data = [
            'model' => $invoice,
            'consumer_id' => $invoice->consumer_id,
            'amount' => $invoice->balance_amount,
        ];
        $add_ledger = LedgerService::create($ledger_data, 'cr');
        // Bill Invoice Update
        $invoice->update([
            'paid_amount' => $invoice->paid_amount + $invoice->balance_amount,
            'balance_amount' => 0,
            'status_id' => InvoiceStatus::CANCEL->value,
            'created_by' => Auth::id(),
        ]);
        // Check if it is GAS Invoice
        if($invoice->invoice_type == InvoiceType::GAS_BILL->value) {
            if($invoice->childInvoices->isNotEmpty()) {
                foreach($invoice->childInvoices as $childInvoice) {
                    $childInvoice->update([
                        'paid_amount' => 0,
                        'balance_amount' => $childInvoice->payable_amount,
                        'status_id' => InvoiceStatus::CANCEL->value,
                        'updated_by' => Auth::id(),
                    ]);
                    // Child Invoice Cancel
                    BillInvoiceCancel::create([
                        'invoice_id' => $childInvoice->id,
                        'reason' => $notes,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }
        // Add Invoice Cancel Record
        BillInvoiceCancel::create([
            'invoice_id' => $id,
            'reason' => $notes,
            'created_by' => Auth::id(),
        ]);
        // response
        return (int) true;
    }
}