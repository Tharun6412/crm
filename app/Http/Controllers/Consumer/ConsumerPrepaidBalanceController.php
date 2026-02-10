<?php
namespace App\Http\Controllers\Consumer;

use App\Contracts\Prepaid\Balance;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\Prepaid;
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
                'crn' => "111260R111", //$consumer->crn,
                'meter_serial_no' => "PG0325004103",//$consumer->activeMeter->meter_serial_no,
            ];
            // Api Response
            $response = $consumerBalance->balance($consumer_data);
            // Convert to JSON
            $balance = $response->json();
            // Success
            if($balance['data']['result']['status'] == "success") {
                // update balance 
                Prepaid::where('consumer_id', $id)->update([
                    'balance' => $balance['data']['result']['current_wallet_balance'],
                    'balance_date' => Carbon::now(),
                ]);
                // Response
                return response()->json([
                    'balance' => $balance['data']['result']['current_wallet_balance'],
                    'balance_date' => Carbon::now()->toDateTimeString(),
                ]);
            }else {
                // Failed Response
                return response()->json([
                    'balance' => 0, 
                    'balance_date' => Carbon::now()->toDateTimeString(), 
                    'message' => "Unable to fetch details due to network"
                ]);
            }
        }
    }
} 