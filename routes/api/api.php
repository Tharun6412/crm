<?php

use App\Http\Middleware\ApiBasicAuth;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\CustomCheckToken;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;

// Routes
Route::middleware([ApiKeyMiddleware::class])->group(function(){
    Route::get('consumer-add', function(){
        return "Consumer Add API";
    });
    Route::post('mro/pushData', [App\Http\Controllers\Api\prepaid\MroPush::class, 'store']);
    Route::post('smartConnect/activate', [App\Http\Controllers\Api\prepaid\SmartConnect::class, 'store']);
});

/**
 * PNGRB Central Portal
 */
/*Route::middleware([ApiBasicAuth::class])->group(function () {
    Route::get('png-application', [App\Http\Controllers\Api\Pngrb\PngApplicationController::class, 'index']);
    // Route::post('png-application', [App\Http\Controllers\Api\Pngrb\PngApplicationController::class, 'store']);
});*/

/**
 * PNGRB Unified portal
 * 
 * OAuth 2.0
 * OAuth token generation API - /oauth/token [grant_type, client_id, client_secret]
 * 
 * Reference with the PNGRB technical document
 */
Route::middleware([CustomCheckToken::class])->group(function() {
    // Receive PNGRB application Ref 4.2
    Route::post('v1/png-application', [App\Http\Controllers\Api\Pngrb\V1\PngApplicationController::class, 'store']);
    // Update application status Ref 4.3
    Route::post('v1/png-application/update', [App\Http\Controllers\Api\Pngrb\V1\PngApplicationController::class, 'update']);
    // Update application status Ref 4.4
    Route::post('v1/png-application/document', [App\Http\Controllers\Api\Pngrb\V1\PngApplicationController::class, 'document']);
});