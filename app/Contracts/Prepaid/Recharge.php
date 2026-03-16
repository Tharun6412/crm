<?php

namespace  App\Contracts\Prepaid;

use App\Contracts\Prepaid\Contract\Polaris;
use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Recharge
{
    /**
     * Push recharge to HES
     */
    public static function push($rechargeData)
    {
        // Call API
        $response = Polaris::postData(PrepaidApi::recharge()->value, $rechargeData);
        return $response;
    }

    /**
     * Cancel Recharge to HES
     */
    public static function cancelRecharge($rechargeData)
    {
        // Call API
        $response = Polaris::postData(PrepaidApi::cancelRecharge()->value, $rechargeData);
        return $response;
    }
}