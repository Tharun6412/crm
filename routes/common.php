<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    // Common services
    Route::get('gaDistricts', [App\Http\Controllers\Services\CommonController::class, 'gaDistricts']);
    Route::get('gaDistrictsSchemes', [App\Http\Controllers\Services\CommonController::class, 'gaDistrictsSchemes']);
    Route::get('districtCas', [App\Http\Controllers\Services\CommonController::class, 'districtCas']);
    Route::get('caAreas', [App\Http\Controllers\Services\CommonController::class, 'caAreas']);
    Route::get('schemeDetails', [App\Http\Controllers\Services\CommonController::class, 'schemeDetails']);
});