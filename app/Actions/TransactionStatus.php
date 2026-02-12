<?php

namespace App\Actions;

use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionStatus
{
    /**
     * Success
     */
    public static function success()
    { 
        return "Transaction success";
    }

    /**
     * Fail
     */
    public static function fail()
    {
        return "Transaction Failure";
    }
}