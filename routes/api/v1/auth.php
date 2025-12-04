<?php

use Illuminate\Support\Facades\Route;

// Guest access
Route::middleware('guest')->group(function() {
    Route::post('auth', [App\Http\Controllers\Api\V1\Auth\AuthenticationController::class, 'login']);
});

// Authenticated
Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [App\Http\Controllers\Api\V1\Auth\AuthenticationController::class, 'logout']);
    Route::get('profile', [App\Http\Controllers\Api\V1\Auth\AuthenticationController::class, 'profile']);
});