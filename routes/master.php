<?php

use Illuminate\Support\Facades\Route;

// Master routes
Route::middleware(['auth'])->group(function() {
    // Master data landing page
    Route::get('/', [App\Http\Controllers\Master\Master\MasterController::class, 'index']);
    
    // Consumer master data
    Route::prefix('consumer')->group(function () {
        Route::resource('prices', App\Http\Controllers\Master\Consumer\PriceController::class);
        // Price groups
        Route::resource('price-groups', App\Http\Controllers\Master\Consumer\PriceGroupsController::class);
        // Schemes
        Route::resource('schemes', App\Http\Controllers\Master\Consumer\SchemesController::class);
        Route::post('schemes/{id}/togglestatus', [App\Http\Controllers\Master\Consumer\SchemesController::class, 'toggleStatus']);
        Route::resource('firmTypes', App\Http\Controllers\Master\Consumer\FirmTypeController::class);
    });

    // Invoice master data
    Route::prefix('invoice')->group(function () {
        Route::resource('addresses', App\Http\Controllers\Master\Invoice\InvoiceAddressController::class);
        Route::resource('items', App\Http\Controllers\Master\Invoice\InvoiceItemsController::class);
        Route::resource('types', App\Http\Controllers\Master\Invoice\InvoiceTypesController::class);
        Route::resource('configuration', App\Http\Controllers\Master\Invoice\ConfigController::class);
    });

    // Payment master data
    Route::prefix('payment')->group(function () {
        Route::resource('types', App\Http\Controllers\Master\Payments\PaymentTypesController::class);
        Route::resource('paymentGateways', App\Http\Controllers\Master\Payments\PaymentGatewayController::class);
    });

    // Complaint master data
    Route::prefix('complaint')->group(function () {
        Route::resource('categories', App\Http\Controllers\Master\Complaint\CategoriesController::class);
        Route::resource('contextual-data', App\Http\Controllers\Master\Complaint\ContextualDataController::class);
    });

    // Locations - States, Clusters, GAs, Districts, Charge areas, areas
    // Invoice master data
    Route::prefix('location')->group(function () {
        Route::resource('states', App\Http\Controllers\Master\Location\StateController::class);
        Route::resource('clusters', App\Http\Controllers\Master\Location\ClusterController::class);
        Route::resource('geo-areas', App\Http\Controllers\Master\Location\GeoAreaController::class);
        Route::resource('districts', App\Http\Controllers\Master\Location\DistrictController::class);
        Route::resource('charge-areas', App\Http\Controllers\Master\Location\ChargeAreaController::class);
        Route::resource('areas', App\Http\Controllers\Master\Location\AreaController::class);
        Route::resource('subareas',App\Http\Controllers\Master\Location\SubAreaController::class);
        Route::resource('industrial-areas', App\Http\Controllers\Master\Location\IndustrialAreaController::class);
    });

    // Documents
    Route::prefix('dc')->group(function() {
        Route::resource('documents', App\Http\Controllers\Master\DocumentCentre\DocumentController::class);
        // Document browser
        Route::resource('browse', App\Http\Controllers\Master\DocumentCentre\DocumentBrowser::class);
        Route::get('sessionFiles', [App\Http\Controllers\Master\DocumentCentre\DocumentBrowser::class, 'sessionFiles']);
        Route::get('deleteFile', [App\Http\Controllers\Master\DocumentCentre\DocumentBrowser::class, 'deleteFile']);
        Route::get('search', [App\Http\Controllers\Master\DocumentCentre\DocumentBrowser::class, 'search']);
        Route::post('selectFiles', [App\Http\Controllers\Master\DocumentCentre\DocumentBrowser::class, 'selectFiles']);
    });

    //Tickets
    Route::prefix('tickets')->group(function() {
        Route::get('/',[App\Http\Controllers\Master\Tickets\CategoriesController::class,'index']);
        Route::get('create',[App\Http\Controllers\Master\Tickets\CategoriesController::class,'create']);
        Route::post('store',[App\Http\Controllers\Master\Tickets\CategoriesController::class,'store']);
        Route::get('edit/{id}/',[App\Http\Controllers\Master\Tickets\CategoriesController::class,'edit']);
        Route::put('update/{id}/',[App\Http\Controllers\Master\Tickets\CategoriesController::class,'update']);
        Route::get('status',[App\Http\Controllers\Master\Tickets\StatusController::class,'index']);
    });
});