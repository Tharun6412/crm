<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

// Admin routes
Route::middleware([ModuleAccess::class, 'auth'])->group(function() {
    // User administration
    Route::get('users/status/{id}', [App\Http\Controllers\Admin\UserStatusController::class, 'edit']);
    Route::put('users/status/{id}', [App\Http\Controllers\Admin\UserStatusController::class, 'update']);
    Route::post('users/reset/{id}', [App\Http\Controllers\Admin\UserController::class, 'reset']);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    //user cas
    Route::get('users/editUserCas/{id}',[App\Http\Controllers\Admin\UserController::class,'editUserCas']);
    Route::post('users/updateUserCas/{id}',[App\Http\Controllers\Admin\UserController::class,'updateUserCas']);
    // user areas
    Route::get('users/editUserAreas/{id}',[App\Http\Controllers\Admin\UserController::class,'editUserAreas']);
    Route::post('users/updateUserAreas/{id}',[App\Http\Controllers\Admin\UserController::class,'updateUserAreas']);
    // Module administration
    Route::get('modules/createSub/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'createSub']);
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);

    // API KEY
    Route::resource('api-keys', App\Http\Controllers\Admin\ApiKeyController::class);
});