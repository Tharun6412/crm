<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            // Web routes
            Route::middleware('web')->group(base_path('routes/auth.php'));
            Route::middleware('web')->prefix('admin')->group(base_path('routes/admin.php'));
            Route::middleware('web')->prefix('master')->group(base_path('routes/master.php'));
            Route::middleware('web')->prefix('dc')->group(base_path('routes/dc.php'));
            Route::middleware('web')->prefix('common')->group(base_path('routes/common.php'));
            Route::middleware('web')->prefix('consumers')->group(base_path('routes/consumer.php'));
            Route::middleware('web')->prefix('spot')->group(base_path('routes/spot.php'));
            Route::middleware('web')->prefix('bill')->group(base_path('routes/billing.php'));
            Route::middleware('web')->group(base_path('routes/complaint.php'));
            
            // API routes
            Route::middleware('api')->prefix('api/v1')->group((base_path('routes/api/v1/auth.php')));
            Route::middleware('api')->prefix('api/v1')->group((base_path('routes/api/v1/app.php')));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
