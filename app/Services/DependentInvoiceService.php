<?php

namespace App\Services;

use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SDPaymentStatus;
use App\Enums\TaxType;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerSdPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class DependentInvoiceService
{
    public static function sdEmiCreate($consumer, $invoice)
    {
        $scheme = $consumer->scheme;
        $emi_balance = $scheme->balance;
        if($emi_balance > 0) {
            $emiAmount  = (float) $scheme->emi_amount;
            $lastEmi = $consumer->sdPayment()->latest()->first();
            $emisPaid = $lastEmi ? (int) $lastEmi->emi_no : 0;
            $invoiceType = InvoiceType::SD_EMI->value; // EMI
            $invoice_total  = $emiAmount;
            $emiNo = $emisPaid + 1;
            $tax_id = TaxType::GST->value;
            $tax_value = 0;
            $tax_amount = 0;

            $invoice_items[] = [
                'item_id' => InvoiceItem::SDEMI->value, // SD EMI
                'quantity' => 1,
                'unit_price' => $invoice_total,
                'total_price' => $invoice_total,
            ];

            $invoice_data = [
                'config' => [
                    'state_id' => $consumer->ga->state_id,
                    'tax_id' => $tax_id,
                ],
                'headers' => [
                    'type_id' => $invoiceType,
                    'consumer_id' => $consumer->id,
                    'invoice_date' => $invoice->invoice_date,
                    'base_amount' => $invoice_total,
                    'taxable_amount' => $invoice_total,
                    'tax_id' => $tax_id,
                    'tax_value' => $tax_value,
                    'tax_amount' => $tax_amount,
                    'total_amount' => $invoice_total,
                    'payable_amount' => $invoice_total,
                    'balance_amount' => $invoice_total,
                    'due_date' => $invoice->due_date,
                    'parent_invoice_id' => $invoice->id,
                    'status_id' => InvoiceStatus::NOT_PAID->value, // Unpaid
                    'created_by' => Auth::id(),
                ],
                'items' => $invoice_items,
            ];
            // Generate Invoice with Invoice Service
            $inv_number = InvoiceService::create($invoice_data);

            // EMI payment insert as charged.
            if ($inv_number) {
                $new_sd_balance = ($emi_balance - $invoice_total); 
                ConsumerSdPayment::create([
                    'consumer_id' => $consumer->id,
                    'invoice_id'  => $inv_number['invoice_id'],
                    'emi_no'      => $emiNo,
                    'amount'      => $invoice_total,
                    'status_id' => SDPaymentStatus::NOT_PAID->value, // Charged.
                    'balance' => $new_sd_balance, // remaining sd balance after this emi.
                    'created_by'  => Auth::id(),
                ]);
                
                // Update the scheme details for every emi invoice generation.
                ConsumerScheme::where('id', $scheme->id)->update([
                    'paid_deposit' => ($scheme->paid_amount + $invoice_total),
                    'balance' => $new_sd_balance,
                    'status' => ($new_sd_balance <= 0) ? 1 : 0, // toggle the status after the final emi generated.
                ]);
            }
        }
    }

    public static function rentalInvCreate($consumer, $invoice)
    {
        $start_date = $invoice->consumption->date_from->format('Y-m-d');
        $end_date = $invoice->consumption->date_to->format('Y-m-d');
        $scheme = $consumer->scheme;
        $totalDays = Carbon::parse($start_date)->diffInDays($end_date) + 1;
        $invoiceType = InvoiceType::RENTAL_CHARGES->value; // Rental
        $tax_id = TaxType::GST->value;
        if ($totalDays > 0) {
            $invoice_total  = round($scheme->rental_amount * $totalDays, 2);
            $rsp = $scheme->rental_amount;
            $tax = 18;
            $basic_price = round(($rsp*(100/(100+$tax))), 2);
            $tax_price = ($rsp - $basic_price);
            $base_amount = round(($basic_price * $totalDays),2);
            $tax_amount = round(($tax_price * $totalDays),2);
        }
        $invoice_items[] = [
            'item_id' => InvoiceItem::RENTAL_CHARGES->value, // Rental item
            'quantity' => $totalDays,
            'unit_price' => $basic_price,
            'total_price' => $base_amount,
        ];

        $invoice_data = [
            'config' => [
                'state_id' => $consumer->ga->state_id,
                'tax_id' => $tax_id,
            ],
            'headers' => [
                'type_id' => $invoiceType,
                'consumer_id' => $consumer->id,
                'invoice_date' => $invoice->invoice_date,
                'base_amount' => $base_amount,
                'taxable_amount' => $base_amount,
                'tax_id' => $tax_id,
                'tax_value' => $tax,
                'tax_amount' => $tax_amount,
                'total_amount' => $invoice_total,
                'payable_amount' => $invoice_total,
                'balance_amount' => $invoice_total,
                'due_date' => $invoice->due_date,
                'parent_invoice_id' => $invoice->id,
                'status_id' => InvoiceStatus::NOT_PAID->value, // Unpaid,
                'created_by' => Auth::id(),
            ],
            'items' => $invoice_items,
        ];
        // Generate Invoice with Invoice Service
        $inv_number = InvoiceService::create($invoice_data);
    }
}