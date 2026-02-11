<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

// Routes
Route::middleware([ApiKeyMiddleware::class])->group(function(){
    Route::get('consumer-add', function(){
        return "Consumer Add API";
    });
});