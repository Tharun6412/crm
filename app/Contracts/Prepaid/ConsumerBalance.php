<?php

namespace  App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class ConsumerBalance
{
    /**
     * Push recharge to HES
     */
    public function balance($balanceData)
    {
        // Call API
        $response = Http::withHeaders([
            'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                ])->acceptJson()->post(PrepaidApi::onDemandRead()->value, $balanceData);
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