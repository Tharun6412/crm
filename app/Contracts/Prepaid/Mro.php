<?php

namespace App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;

class Mro
{
    /**
     * Request
     */
    public function request($consumer_details)
    {
        $chunks = array_chunk($consumer_details, 100);
        $resp_data_success =  array();
        foreach ($chunks as $key => $chunk) { 
            $cns_ar = [];
            $cns_ar['MT_MRO_Request']['MRO_Request'] = $chunk; 

            // Call API
            $response_ext = Http::withHeaders([
                'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                    ])->acceptJson()->post(PrepaidApi::mroRequest()->value, $cns_ar);
            if ($response_ext->failed()) {
                logger()->error('HES API FAILED', [
                    'status'  => $response_ext->status(),
                    'body'    => $response_ext->body(),
                    'payload' => $cns_ar,
                ]);
            }
            $response_decode = json_decode($response_ext, true);
            if(isset($response_decode['MT_MRO_Response']) and !empty($response_decode['MT_MRO_Response'])) {
                if(isset($response_decode['MT_MRO_Response']['MRO_Response']) and !empty($response_decode['MT_MRO_Response']['MRO_Response'])) {
                    foreach ($response_decode['MT_MRO_Response']['MRO_Response'] as $mro_response) {
                         $resp_data_success['MT_MRO_Response']['MRO_Response'][] = $mro_response;
                    }
                }
            }
        }
        return $resp_data_success;
    }
}