<?php

namespace App\Services;

use App\Enums\Constants;
use App\Enums\ReferralStatus;
use App\Models\Consumer\ConsumerData;
use App\Models\Consumer\Referral;
use App\Models\Consumer\ReferralConsumer;
use Illuminate\Validation\ValidationException;

class ReferralService 
{
    /**
     * Generate a new unique Referral code for every consumer
     */
    public static function generateReferralCode()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (
            ConsumerData::where('reference_code', $code)->exists()
        );
        return $code;
    }
    /**
     * Validation function for Referral code
     * @param object $data
     */
    public static function checkValidation($data)
    {
        $referral_id = 0;
        $referrer_id = "";
        if(!empty($data->referral_code))
        {
            $mobile_no = $data->phone;
            $referal_code = $data->referral_code;
            $ref_request = Referral::where('phone', $mobile_no)->first();
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
                        $referrer_id = $ref_request->consumer->id;
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
                    return ['referral_id' => $referral_id, 'matched' => $matched, 'referrer_id' => $referrer_id];

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
        $ref_amt = ReferralConsumer::where('referral_consumer_id', $consumer_id)->where('status', ReferralStatus::PROCESSING->value)->first();
        if($ref_amt)
        {
            $redeem = $ref_amt->update(['status' => ReferralStatus::EARNED->value]);

            return ['status' => true, 'message' => 'Successfully redeemed'];
        }
        else {
            return ['status' => false, 'message' => 'No redemption data found.'];

        }
        
    }
}