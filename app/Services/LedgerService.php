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
    public static function create(array $ledger_data)
    {
        foreach($ledger_data as $key => $data) {
            // model instance
            $model = $data['model'];
            $model->ledger()->create([
                'consumer_id' => $data['consumer_id'],
                'description' => $data['description'],
                'credit' => $data['credit'],
                'debit' => $data['debit'],
                'balance' => $data['balance'],
            ]);
        }
    }
}
