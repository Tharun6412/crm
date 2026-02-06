<?php

namespace App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Mro
{
    /**
     * Request
     */
    public function request()
    {
        $consumer_details = [];
        // Call API
        $response = Http::withHeaders([
            'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                ])->acceptJson()->post(PrepaidApi::mroRequest()->value, $consumer_details);
        if ($response->failed()) {
            logger()->error('HES API FAILED', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $consumer_details,
            ]);
        }
        return $response;
    }

    
}