<?php

namespace App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Mro
{
    /**
     * Request
     * @param data_array
     * @param api_id
     * @return api_response (array)
     */
    public static function request($consumer_details, $api_id)
    {
        $chunks = array_chunk($consumer_details, 100);
        $responses =  array();
        foreach ($chunks as $key => $chunk) { 
            $cns_ar = [];
            $cns_ar['MT_MRO_Request']['MRO_Request'] = $chunk; 

            // Call API
            $response = Http::withHeaders([
                'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                    ])->acceptJson()->post($api_id, $cns_ar);
            if ($response->failed()) {
                logger()->error('HES API FAILED', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'payload' => $cns_ar,
                ]);
            }
            
            $body = $response->json();
            $responses = array_merge($responses,$body['MT_MRO_Response']['MRO_Response'] ?? []);
        }
        return $responses;
    }
}