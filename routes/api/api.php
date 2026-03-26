<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

// Routes
Route::middleware([ApiKeyMiddleware::class])->group(function(){
    Route::get('consumer-add', function(){
        return "Consumer Add API";
    });
    Route::post('mro/pushData', [App\Http\Controllers\Api\prepaid\MroPush::class, 'store']);
    Route::post('smartConnect/activate', [App\Http\Controllers\Api\prepaid\SmartConnect::class, 'store']);
});