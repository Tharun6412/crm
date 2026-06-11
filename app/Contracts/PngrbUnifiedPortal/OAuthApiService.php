<?php

namespace App\Contracts\PngrbUnifiedPortal;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OAuthApiService
{
    private string $tokenUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->tokenUrl     = 'http://15.207.185.179:8080/realms/pngrb-realm/protocol/openid-connect/token';//config('services.oauth_api.token_url');
        $this->clientId     = 'cgd-192';//config('services.oauth_api.client_id');
        $this->clientSecret = 'LyluqQzYfsQ3oR4JwsTEDS4Hc89Vp6nc';//config('services.oauth_api.client_secret');
    }

    /**
     * Fetch token (with caching to avoid redundant calls)
     */
    private function getAccessToken(): string
    {
        return Cache::remember('oauth_access_token', 300, function () {
            try {
                $response = Http::asForm()->post($this->tokenUrl, [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

                $response->throw(); // throws on 4xx/5xx
                // echo $response;
                return $response->json('access_token');
            }
            catch (\Exception $e) {
                // 
                return 'access_token';
            }
        });
    }

    /**
     * Single public method — handles token + API call in one go
     */
    public function get(string $url, array $query = []): array
    {
        try {
            $response = Http::withToken($this->getAccessToken())
            ->get($url, $query)
            ->throw()
            ->json();
        } catch (\Throwable $th) {
            $response = 'Failed!';
        }

        return $response;
    }

    /**
     * POST method with Authentication token
     */
    public function post(string $url, array $data = []): array
    {
        // Get Access token
        $access_token = $this->getAccessToken();
        try {
            $response = Http::withToken($access_token)
                ->post($url, $data)
                ->throw();
            return $response->json();
        }
        catch (ConnectionException $e) {
            return [
                'success' => false,
                'message' => 'Connection failed.',
            ];
        }
        catch (RequestException $e) {
            // Get response
            return $e->response->json();
        }
        catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Unexpected error occurred.',
            ];
        }

        throw $e;
    }
}