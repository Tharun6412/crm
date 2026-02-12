<?php

namespace App\Http\Middleware;

use App\Models\Admin\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');

        if (!$key) {
            return response()->json(['message' => 'API key missing'], 401);
        }

        $hashedKey = hash('sha256', $key);

        $apiKey = Cache::remember(
            'api_key_' . $hashedKey,
            60,
            fn () => ApiKey::where('key', $hashedKey)->first()
        );

        if (
            !$apiKey ||
            !$apiKey->is_active ||
            ($apiKey->expires_at && $apiKey->expires_at->isPast())
        ) {
            return response()->json(['message' => 'Invalid API key'], 403);
        }

        return $next($request);
    }
}