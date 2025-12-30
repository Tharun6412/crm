<?php

namespace App\Services;

use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use Illuminate\Support\Facades\Auth;

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
            'code' => '',
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
        $invoice->status_id = ($balance > 0) ? 2 : 1;
        $invoice->save();

        // Add ledger record
        $ledger_data[] = [
            'model' => $new_payment,
            'consumer_id' => $new_payment->invoice->consumer_id,
            'description' => "Bill Payment: Invoice Generated with Invoice No.",
            'credit' => $new_payment->amount,
            'debit' => NULL,
            'balance' => $new_payment->amount, 
        ];
        // Call Ledger Service
        LedgerService::create($ledger_data, "credit");

        // Return payment object
        return $new_payment;
    }
}