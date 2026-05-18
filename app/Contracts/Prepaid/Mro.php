<?php

namespace App\Contracts\Prepaid;

use App\Contracts\Prepaid\Contract\Polaris;
use App\Enums\PrepaidApi;
use App\Helpers\ApiLogger;
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
            if($api_id == PrepaidApi::mroAcknowledgment()->value){
                $cns_ar['response'] = $chunk; 
            }
            else {
                $cns_ar['MT_MRO_Request']['MRO_Request'] = $chunk; 
            }
            ApiLogger::info('mro_api','mro', 'Sending chunk', [
                'chunk_index' => $key,
                'chunk_size'  => count($chunk),
            ]);
            // Call API
            $response = Polaris::postData($api_id, $cns_ar);
            $body = $response->json();
            if(!empty($body['MT_MRO_Response']['MRO_Response'])) {
                $responses = array_merge($responses,$body['MT_MRO_Response']['MRO_Response'] ?? []);
            }else {
                ApiLogger::warning('mro_api', 'mro', 'Unexpected response structure', [
                    'chunk_index' => $key,
                    'body'        => $body,
                ]);
            }
        }
        return $responses;
    }
}