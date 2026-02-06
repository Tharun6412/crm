<?php

namespace  App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Recharge
{
    /**
     * Push recharge to HES
     */
    public function push($rechargeData)
    {
        // Call API
        $response = Http::withHeaders([
            'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                ])->acceptJson()->post(PrepaidApi::recharge()->value, $rechargeData);
        if ($response->failed()) {
            logger()->error('HES API FAILED', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $rechargeData,
            ]);
        }
        return $response;
    }
}