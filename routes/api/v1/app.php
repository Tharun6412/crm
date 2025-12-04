<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Common services
    Route::get('gaDistricts', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'gaDistricts']);
    Route::get('gaDistrictsSchemes', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'gaDistrictsSchemes']);
    Route::get('districtCas', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'districtCas']);
    Route::get('caAreas', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'caAreas']);
    Route::get('schemeDetails', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'schemeDetails']);

    // Consumers routes
    Route::prefix('consumer')->group(function () {
        Route::get('list', [App\Http\Controllers\Api\V1\Application\ConsumerController::class, 'list']);
    });
});