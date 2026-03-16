<?php
namespace App\Contracts\Prepaid\Contract;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Polaris
{
    public static function postData($api, $payload = NULL)
    {
        if($payload) {
            try{
                $response = Http::withHeaders([
                    'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                        ])->acceptJson()->post($api, $payload);
                if ($response->failed()) {
                    Log::error('HES API FAILED', [
                        'status'  => $response->status(),
                        'body'    => $response->body(),
                        'payload' => $payload,
                    ]);
                }
                return $response;
            }
            catch(\Throwable $e)
            {
                Log::error('HES API FAILED', [
                    'status'  => 0,
                    'body'    => $e->getMessage(),
                ]);
            }
        }
        else {
            Log::error('HES API FAILED', [
                'status'  => 0,
                'body'    => "No payload data available.",
            ]);

            return false;
        }
    }

    public static function getData($api, $payload = NULL)
    {
        if($payload) {
            try{
                $response = Http::withHeaders([
                    'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                        ])->acceptJson()->get($api, $payload);
                if ($response->failed()) {
                    Log::error('HES API FAILED', [
                        'status'  => $response->status(),
                        'body'    => $response->body(),
                        'payload' => $payload,
                    ]);
                }
                return $response;
            }
            catch(\Throwable $e)
            {
                Log::error('HES API FAILED', [
                    'status'  => 0,
                    'body'    => $e->getMessage(),
                ]);
            }
        }
        else {
            Log::error('HES API FAILED', [
                'status'  => 0,
                'body'    => "No payload data available.",
            ]);

            return false;
        }
    }
}