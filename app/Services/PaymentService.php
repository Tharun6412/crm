<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\PaymentReversal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Create payment
     */
    public static function create($data)
    {
        // Get invoice details
        $invoice = BillInvoice::find($data['invoice_id']);
        $balance = $invoice->balance_amount - $data['amount'];

        // Check if payment type is cheque
        // Insert into cheque model

        // Insert payment data
        $new_payment = InvoicePayment::create([
            'code' => Str::random(6),
            'invoice_id' => $data['invoice_id'],
            'payment_date' => $data['payment_date'],
            'payment_type_id' => $data['payment_type_id'],
            'transaction_id' => $data['transaction_id'],
            'amount' => $data['amount'],
            'balance' => $balance,
            'notes' => $data['notes'],
            'created_by' => Auth::id(),
            'status_id' => $data['status_id'], //1 = Completed
        ]);

        // Update invoice paid and balances
        $invoice->paid_amount = $invoice->paid_amount + $data['amount'];
        $invoice->balance_amount = $balance;
        $invoice->status_id = ($balance > 0) ? InvoiceStatus::NOT_PAID->value : InvoiceStatus::PAID->value; //2 = NotPaid, 1=PAID
        $invoice->save();

        // Add record to Ledger
        $ledger_record = LedgerService::create([
            'model' => $new_payment,
            'consumer_id' => $new_payment->invoice->consumer_id,
            'amount' => $data['amount'],
        ], "cr");

        // Return payment object
        return $new_payment;
    }
}