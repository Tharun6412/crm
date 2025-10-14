<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware(['guest'])->group(function() {
    // Module administration
    Route::get('modules/createSub/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'createSub']);
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
});