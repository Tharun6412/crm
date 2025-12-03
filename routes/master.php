<?php

use Illuminate\Support\Facades\Route;

// Master routes
Route::middleware(['auth'])->group(function() {
    // Consumer master data
    Route::prefix('consumer')->group(function () {
        Route::resource('schemes', App\Http\Controllers\Master\Consumer\SchemesController::class);
        Route::resource('prices', App\Http\Controllers\Master\Consumer\PriceController::class);
        Route::post('schemes/{id}/togglestatus', [App\Http\Controllers\Master\Consumer\SchemesController::class, 'toggleStatus']);

    });

    // States, Clusters, GAs, Districts, Charge areas, areas
    Route::resource('states', App\Http\Controllers\Master\Master\StateController::class);
    Route::resource('clusters', App\Http\Controllers\Master\Master\ClusterController::class);
    Route::resource('geo-areas', App\Http\Controllers\Master\Master\GeoAreaController::class);
    Route::resource('districts', App\Http\Controllers\Master\Master\DistrictController::class);
    Route::resource('charge-areas', App\Http\Controllers\Master\Master\ChargeAreaController::class);
    Route::resource('areas', App\Http\Controllers\Master\Master\AreaController::class);
});