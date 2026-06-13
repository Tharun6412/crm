<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Http\Middleware\CheckToken;
use Throwable;

class CustomCheckToken extends CheckToken
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$params
    ): Response
    {
        try {
            return parent::handle($request, $next, ...$params);
        } catch (Throwable $e) {

            return response()->json([
                'success'  => false,
                'statusCode' => 401,
                'message' => 'Unauthorized',
                'errors'    => [
                    ["field" => "authorization", "message" => "Invalid or missing authentication token. Please provide a valid Bearer token."],
                ],
            ], 401);
        }
    }
}