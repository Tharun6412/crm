<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Invoice\PaymentReversal;
use App\Models\Payments\PayAdvance;
use App\Models\Payments\PayAdvanceTransaction;
use Carbon\Carbon;
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
            'pay_transaction_id' => $data['pay_transaction_id'] ?? null,
            'amount' => $data['amount'],
            'balance' => $balance,
            'notes' => $data['notes'],
            'created_by' => Auth::id(),
            'status_id' => $data['status_id'], //1 = Completed
        ]);

        // Advance Payment
        if($invoice->type_id == InvoiceType::GAS_BILL->value) {
            if($balance < 1) {
                // Add or Update in Advance Table
                $advance_pay = PayAdvance::firstOrNew(['consumer_id' => $invoice->consumer_id]);
                $advance_pay->advance_amount = ($advance_pay->advance_amount ?? 0) + abs($balance ?? 0);
                $advance_pay->updated_at = Carbon::now();
                $advance_pay->save();
                // Create ian Advance Transaction
                $new_payment->advance()->create([
                    'amount' => abs($balance ?? 0),
                    'balance' => $advance_pay->advance_amount,
                ]);
            }
        }
        // Update invoice paid and balances
        // $invoice->paid_amount = $invoice->paid_amount + $data['amount'];
        $paid_amt = $invoice->paid_amount + $data['amount'];
        $invoice->paid_amount = min($paid_amt, $invoice->payable_amount);
        $invoice->balance_amount = max(0, (float)$balance);
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

    /**
     * Payment Reversal
     */
    public static function reversal($payment, string $notes) : bool
    {
        if($payment->status_id == PaymentStatus::REVERSAL->value) {
            return (int) true;
        }
        // Use fresh invoice instance
        $invoice = BillInvoice::find($payment->invoice_id);
        // Add to Ledger Record
        $ledger_data = [
            'model' => $payment,
            'consumer_id' => $invoice->consumer_id,
            'amount' => $invoice->payable_amount,
        ];
        $add_ledger = LedgerService::create($ledger_data, 'dr');
        // Bill Invoice Update
        $invoice->update([
            'paid_amount' => 0,
            'balance_amount' => $invoice->payable_amount,
            'status_id' => InvoiceStatus::NOT_PAID->value,
            'updated_by' => Auth::id(),
        ]);
        // Update Payment record
        $payment->update([
            'amount' => 0,
            'balance' => $invoice->payable_amount,
            'status_id' => PaymentStatus::REVERSAL->value,
        ]);
        // Add Payment Reversal record
        PaymentReversal::create([
            'payment_id' => $payment->id,
            'notes' => $notes,
            'created_by' => Auth::id(),
        ]);
        return (int) true;
    }
}