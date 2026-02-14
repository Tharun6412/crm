<?php

namespace App\Services;

use App\Contracts\Prepaid\Recharge;
use App\Enums\PaymentStatus;
use App\Models\Payments\PayRecharge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RechargeService
{
    /**
     * Create Recharge
     */
    public static function create(array $transaction_data, array $recharge_data)
    {
        // Recharge Data Preparation
        $recharge_data = [
            'recharge_request' => [
                'ca_num' => $transaction_data['ca_num'],
                'amount' => $transaction_data['amount'],
                'ref_num' => $transaction_data['ref_num'],
                'utr_num' => $transaction_data['utr_num'],
                'trans_date' => Carbon::now()->toDateString(),
                'mobile_num' => $transaction_data['mobile_num'],
            ]
        ];
        //-- Send data to Polaris HES
        $response = Recharge::push($recharge_data);
        $response_data = $response->json();
        // response
        if($response_data['recharge_response']['error_code'] == 1) {
            return [
                'status' => (int) false,
                'message' => $response_data['recharge_response']['message'],
            ];
        }else {
            PayRecharge::create($recharge_data);
            return [
                'status' => (int) true,
                'message' => 'Recharge payment added successfully',
            ];
        }
    }

    /**
     * Payment Reversal
     */
    public static function cancel(array $transaction_data, int $id)
    {
        // Data Preparation
        $recharge_cancel_data = array(
            'crn' => $transaction_data['ca_num'],
            'meter_serial_no' => $transaction_data['meter_serial_no'],
            'utr_num' => $transaction_data['ref_num'],
            'ref_num' => $transaction_data['utr_num'],
            'trans_date' => $transaction_data['trans_date'],
            'amount' => $transaction_data['amount'],
        );
        $response = Recharge::cancelRecharge($recharge_cancel_data);
        $response_data = $response->json();
        if($response_data['responseCode'] == 404) {
            return [
                'status' =>(int) false,
                'message' => "Consumer recharge data not found",
            ];
        }else {
            $recharge = PayRecharge::where('transaction_id', $id)->first();
            // Update Payment record
            $recharge->update([
                'amount' => $recharge->balance,
                'balance' => $recharge->amount,
                'status_id' => PaymentStatus::REVERSAL->value,
                'updated_by' => Auth::id(),
            ]);
            // response
            return [
                'status' => (int) true,
                'message' => "Recharge updated to failed successfully", 
            ];
        }
    }
}