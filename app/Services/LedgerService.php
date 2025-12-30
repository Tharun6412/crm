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
        foreach($ledger_data as $key => $data) {
            $ledger = Ledger::where('consumer_id', $data['consumer_id'])->latest('id')->first();
            if($type == 'debit') {
                $balance = $ledger ? ($ledger->balance ?? 0) + $data['balance'] : $data['balance'];
            }else {
                $balance = $ledger ? ($ledger->balance ?? 0) - $data['balance'] : $data['balance'];
            }
            // model instance
            $model = $data['model'];
            $model->ledger()->create([
                'consumer_id' => $data['consumer_id'],
                'description' => $data['description'],
                'credit' => $data['credit'],
                'debit' => $data['debit'],
                'balance' => $balance,
            ]);
        }
    }
}
