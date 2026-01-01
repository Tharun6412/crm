<?php

namespace App\Services;

use App\Models\Invoice\Ledger;
use Illuminate\Support\Facades\DB;

class LedgerService 
{
    /**
     * Ledger Report
     * #invoice|payments|creditnote
     */
    public static function create(array $ledger_data, string $type)
    {
        // Get latest balance from ledger for the sent consumer
        $last_ledger = Ledger::where('consumer_id', $ledger_data['consumer_id'])->latest('id')->first();
        $amount = ($type == 'cr') ? -($ledger_data['amount']) : $ledger_data['amount'];
        $new_balance =  ($last_ledger->balance ?? 0) + $amount;
        
        // model instance
        $model = $ledger_data['model'];
        // Insert Ledger record
        $new_ledger_record = $model->ledger()->create([
            'consumer_id' => $ledger_data['consumer_id'],
            'description' => self::context($model, $type),
            'credit' => ($type == 'cr') ? $ledger_data['amount'] : 0,
            'debit' => ($type == 'dr') ? $ledger_data['amount'] : 0,
            'balance' => $new_balance,
        ]);
        
        // Return
        return $new_ledger_record;
    }

    /**
     * Context
     * 
     * @param model
     * @param type cr / dr
     */
    public static function context($model, $type)
    {
        // Find class name
        $cls = class_basename($model);

        // Create context based on the model
        switch($cls) {
            case 'BillInvoice':
                $desc = ($model->invoiceType->name ?? 'Invoice');
                break;

            case 'InvoicePayment':
                $desc = 'Payment';
                break;

            case 'CreditNote':
                $desc = 'Credit / Debit Note';
                break;

            default:
                $desc = 'Unknown';
                break;
        }

        // Return
        return $desc;
    }
}
