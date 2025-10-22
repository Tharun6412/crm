<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware([ModuleAccess::class, 'auth'])->group(function() {
    // User administration
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    // Module administration
    Route::get('modules/createSub/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'createSub']);
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
});