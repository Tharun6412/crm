<?php

namespace  App\Contracts\Prepaid;

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
        $response = Http::acceptJson()->get(PrepaidApi::onDemandRead()->value, $balanceData);
        if ($response->failed()) {
            logger()->error('HES API FAILED', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $balanceData,
            ]);
        }
        return $response;
    }
}