<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::onceBasic($field = 'emp_id');
        if ($auth) {
            // return $auth;
            return response()->json([
                'acknowledged' => true,
                'receivedAt' => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
                'message' => 'Invalid or Missing Token',
            ], 401, [
                'WWW-Authenticate' => 'Basic'
            ]);
        }
        // Check status
        $user = Auth::user();
        if($user->status_id !== UserStatus::ACTIVE->value) {
            return response()->json([
                'acknowledged' => true,
                'receivedAt' => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
                'message' => 'Invalid or Missing Token',
            ], 401);
        }

        return $next($request);
    }
}
