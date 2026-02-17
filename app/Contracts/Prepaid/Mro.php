<?php

namespace App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Mro
{
    /**
     * Request
     */
    public static function request($consumer_details)
    {
        $chunks = array_chunk($consumer_details, 100);
        $resp_data_success =  array();
        foreach ($chunks as $key => $chunk) { 
            $cns_ar = [];
            $cns_ar['MT_MRO_Request']['MRO_Request'] = $chunk; 

            // Call API
            $response = Http::withHeaders([
                'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                    ])->acceptJson()->post(PrepaidApi::mroRequest()->value, $cns_ar);
            if ($response->failed()) {
                logger()->error('HES API FAILED', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'payload' => $cns_ar,
                ]);
            }
            
            print "<pre>"; print_r($response); print"</pre>";
        }
        // return $resp_data_success;
    }
}