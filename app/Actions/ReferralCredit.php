<?php

namespace App\Actions;

use App\Enums\ConsumerStatus;
use App\Enums\PaymentType;
use App\Enums\ReferralStatus;
use App\Helpers\ApiLogger;
use App\Models\Consumer\Referral;
use App\Models\Consumer\ReferralConsumer;
use App\Models\Payments\PayAdvance;
use App\Services\RechargeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReferralCredit
{
    public static function execute()
    {
       $results = [
            'referrer' => ['postpaid' => 0, 'prepaid' => 0, 'failed' => 0, 'skipped' => 0],
            'referral' => ['postpaid' => 0, 'prepaid' => 0, 'failed' => 0, 'skipped' => 0],
        ];
        // Fetching the referrer consumers
        $eligibleReferrers = ReferralConsumer::with([
                'request.consumer:id,crn,t_crn,fname,lname,connection_type_id,phone',
            ])
            ->where('status', ReferralStatus::EARNED->value)
            ->where(fn($q) => $q->where('referrer_redeem_status', 1))
            ->where(fn($q) => $q->whereHas('request.consumer', function ($q) {
                $q->where('status_id', ConsumerStatus::ACTIVATE->value)
                ->where(function ($q) {
                    $q->where('connection_type_id', 1) // postpaid — no commission gate
                        ->orWhereHas('prepaidData', fn($q) => $q->where('hes_status', 1)->where('commission_status', 1));
                });
            }))
            ->get();
        // Fetching the referral consumers
        $eligibleReferrals = ReferralConsumer::with([
                'consumer:id,crn,t_crn,fname,lname,connection_type_id,phone',
            ])
            ->where('status', ReferralStatus::EARNED->value)
            ->where(fn($q) => $q->where('referral_redeem_status', 1))
            ->where(fn($q) => $q->whereHas('consumer', function ($q) {
                $q->where('status_id', ConsumerStatus::ACTIVATE->value)
                ->where(function ($q) {
                    $q->where('connection_type_id', 1) // postpaid — no commission gate
                        ->orWhereHas('prepaidData', fn($q) => $q->where('hes_status', 1)->where('commission_status', 1));
                });
            }))
            ->get();

        if($eligibleReferrers->isNotEmpty()) {
            foreach ($eligibleReferrers as $rc) {

                // Referrer Credit
                $consumer = $rc->request?->consumer;
                if ($consumer) {
                    // Calling the credit function 
                    $credited = self::credit($consumer,$rc->referrer_amount,$rc->request);
                    if ($credited) {
                        // Update the referrer redeem status.
                        $rc->update(['referrer_redeem_status' => 0, 'referrer_redeem_date' => now()->toDateString()]);
                        $type = $consumer->connection_type_id == 1 ? 'postpaid' : 'prepaid';
                        $results['referrer'][$type]++;
                    } else {
                        // Keep status=1 so next job run retries
                        $results['referrer']['failed']++;
                    }
                }
                else {
                    ApiLogger::warning('Referrals', 'referrer-loop', 'Consumer relation null, skipping', [
                        'rc_id' => $rc->id,
                    ]);
                    $results['referrer']['skipped']++;
                }
            }
        }
        if($eligibleReferrals->isNotEmpty()){
            foreach ($eligibleReferrals as $rc) {
                
                // Referral Consumer Credit
                $consumer = $rc->consumer;
                if ($consumer) {
                    // Calling the credit function
                    $credited = self::credit($consumer,$rc->referral_amount,$rc);

                    if ($credited) {
                        $rc->update(['referral_redeem_status' => 0, 'referral_redeem_date' => now()->toDateString()]);
                        $type = $consumer->connection_type_id == 1 ? 'postpaid' : 'prepaid';
                        $results['referral'][$type]++;
                    } else {
                        // Keep status=1 so next job run retries
                        $results['referral']['failed']++;
                    }
                }
                else {
                    ApiLogger::warning('Referrals', 'referral-loop', 'Consumer relation null, skipping', [
                        'rc_id' => $rc->id,
                    ]);
                    $results['referral']['skipped']++;
                }
            }
        }

        return $results;

    }

    /**
     *   Route to correct credit method based on connection type ──────────
     *  @param object $consumer
     *  @param float $amount
     *  
     **/ 
    private static function credit($consumer, float $amount, Model $morphable)
    {
        return match((int) $consumer->connection_type_id) {
            1 => self::creditPostpaid($consumer->id, $amount, $morphable),
            2 => self::creditPrepaid($consumer, $amount),
            default => false,
        };
    }

    /**
     *  Postpaid: insert into pay_advances
     * @param int $consumerId
     * 
     * */ 
    private static function creditPostpaid(int $consumerId, float $amount, Model $morphable)
    {
        try {
           $payAdvance = PayAdvance::where('consumer_id', $consumerId)->lockForUpdate()->first();

            if ($payAdvance) {
                // Exists — increment the amount
                $payAdvance->increment('advance_amount', $amount,['updated_at' => now()->toDateTimeString()]);
                $closingBalance = $payAdvance->advance_amount;
            } else {
                // Not exists — create new record
                $payAdvance = PayAdvance::create([
                    'consumer_id' => $consumerId,
                    'advance_amount' => $amount,
                    'updated_at' => now()->toDateTimeString(),
                ]);
                $closingBalance = $payAdvance->advance_amount;
            }
            // Insert the polymorphic transaction record via the relation
            $morphable->advance()->create([
                'consumer_id' => $consumerId,
                'amount'      => $amount,
                'balance' => $closingBalance,
                'transaction_no' => "Referral reward - ".$consumerId,
                // add any other PayAdvanceTransaction columns here
            ]);
            return true;

        } catch (\Throwable $e) {
            ApiLogger::error('Referrals','postpaid','Postpaid pay_advance insert failed', [
                'consumer_id' => $consumerId,
                'amount'      => $amount,
                'error'       => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     *  Prepaid: hit recharge API
     *  @param object $consumer
     * */ 
    private static function creditPrepaid($consumer, float $amount)
    {
        try{

            $transaction_data = [
                'consumer_id' => $consumer->id,
                'ca_num' => $consumer->crn,
                'amount' => $amount,
                'utr_num' => 'Referral-rewards'.now()->toTimeString(),
                'mobile_num' => $consumer->phone,
            ];
            $add_recharge = RechargeService::create($transaction_data, [
                'consumer_id' => $transaction_data['consumer_id'],
                'recharge_date' => Carbon::now()->toDateString(),
                'amount' => $transaction_data['amount'],
                'balance' => 0,
                'payment_type_id' => PaymentType::REFERRAL_REWARDS->value,
                'transaction_id' => NULL,
            ]);
            if($add_recharge['status'] == 1) {
                return true;
            }else {
                return false;
            }
        }
        catch (\Throwable $e) {
            ApiLogger::error('Referrals','prepaid','prepaid recharge initiation failed', [
                'consumer_id' => $consumer->id,
                'amount'      => $amount,
                'error'       => $e->getMessage(),
            ]);

            return false;
        }
    }
}