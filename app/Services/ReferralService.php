<?php

namespace App\Services;

use App\Enums\Constants;
use App\Enums\ReferralStatus;
use App\Models\Consumer\ReferralRequest;
use Illuminate\Validation\ValidationException;

class ReferralService 
{
    /**
     * Validation function for Referral code
     * @param object $data
     */
    public static function checkValidation($data)
    {
        $referral_id = 0;
        if(!empty($data->referral_code))
        {
            $mobile_no = $data->phone;
            $referal_code = $data->referral_code;
            $ref_request = ReferralRequest::where('phone', $mobile_no)->first();
            if ($ref_request) {
                $matched = false;
                $ref_redeems_count = $ref_request->referralConsumers()->count();
                if ($ref_redeems_count >= 5) {
                    // return response()->json(['error' => 'Referral redemption limit of 5 has been reached.'], 422);
                    throw ValidationException::withMessages([
                        'referral_code' => ['Referral redemption limit of 5 has been reached.'],
                    ]);
                }
                else {
                    $reference_code = $ref_request->consumer?->consumerData?->reference_code;

                    if ($referal_code == $reference_code) {
                        $referral_id = $ref_request->id;
                        $matched = true;
                    }
                }
                    
                if (!$matched) {
                    // return response()->json(['errors' => ['referral_code'=> ["Invalid Referal code."]]]);
                    throw ValidationException::withMessages([
                        'referral_code' => ['Invalid Referral Code.'],
                        ]);
                }
                else {
                    return ['referral_id' => $referral_id, 'matched' => $matched];

                }
            } else {
                // return response()->json(['error' => 'No referral request found for this phone number.'], 422);
                throw ValidationException::withMessages([
                        'referral_code' => ['No referral request found for this phone number.'],
                    ]);
            }
        }
    }
    
    /**
     * Function to update the redeem data. if applicable
     * @param int $consumer_id
     */
    public static function redeem($consumer_id)
    {
        $ref_amt = ReferralRequest::where('referral_consumer_id', $consumer_id)->where('status', ReferralStatus::OPEN->value)->first();
        if($ref_amt)
        {
            $redeem_amt = Constants::REFERRAL_AMOUNT->value;
            $redeem = $ref_amt->update(['status' => ReferralStatus::CLOSE->value ,'reedem_date' => now()->toDateString(), 'reedem_amount' => $redeem_amt]);

            return ['status' => true, 'message' => 'Successfully redeemed'];
        }
        else {
            return ['status' => false, 'message' => 'No redemption data found.'];

        }
        
    }
}