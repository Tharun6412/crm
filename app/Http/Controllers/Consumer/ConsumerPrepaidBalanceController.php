<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\ConsumerBalance;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Illuminate\Http\Request;


class ConsumerPrepaidBalanceController extends Controller
{
    /**
     * Execute consumer form
     */
    public function prepaidBalance(ConsumerBalance $consumerBalance, $id) 
    {
        $consumer = Consumer::find($id);
        if($consumer->connection_type_id == 2 and !empty($consumer->activeMeter->meter_serial_no)) {
            // Data Preparation for Updated Balance
            $consumer_data = [
                'crn' => $consumer->crn,
                'meter_serial_no' => $consumer->activeMeter->meter_serial_no,
            ];
            print "<pre>"; print_r($consumer_data); exit;
            $balance = $consumerBalance->balance($consumer_data);
        }
    }
} 