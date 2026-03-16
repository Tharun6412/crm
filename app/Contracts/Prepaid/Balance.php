<?php

namespace  App\Contracts\Prepaid;

use App\Contracts\Prepaid\Contract\Polaris;
use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Balance
{
    /**
     * Push recharge to HES
     */
    public function balance($balanceData)
    {
        // Call API
        $response = Polaris::postData(PrepaidApi::onDemandRead()->value, $balanceData);
        return $response;
    }
}