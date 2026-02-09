<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\Balance;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ConsumerPrepaidBalanceController extends Controller
{
    /**
     * Execute consumer form
     */
    public function prepaidBalance(Balance $consumerBalance, $id) 
    {
        $consumer = Consumer::find($id);
        if($consumer->connection_type_id == 2 and !empty($consumer->activeMeter->meter_serial_no)) {
            // Data Preparation for Updated Balance
            $consumer_data = [
                'crn' => "111260R211", //$consumer->crn,
                'meter_serial_no' => "PG0325004103",//$consumer->activeMeter->meter_serial_no,
            ];
            // Api Response
            $response = $consumerBalance->balance($consumer_data);
            // Convert to JSON
            $balance = $response->json();
            $balance_updated_date = $balance['data']['result']['current_date_time'];
            return response()->json([
                'balance' => $balance['data']['result']['current_wallet_balance'],
                'balance_date' => Carbon::parse($balance_updated_date)->toDateTimeString(),
            ]);
        }
    }
} 