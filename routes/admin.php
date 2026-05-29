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


    // Module administration
    Route::get('modules/createSub/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'createSub']);
    Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);

    // API KEY
    Route::resource('api-keys', App\Http\Controllers\Admin\ApiKeyController::class);

    //Teams
    Route::get('teams/',[App\Http\Controllers\Admin\TeamController::class,'index']);
    Route::get('teams/create',[App\Http\Controllers\Admin\TeamController::class,'create']);
    Route::post('teams/store',[App\Http\Controllers\Admin\TeamController::class,'store']);
    Route::get('teams/gaCas',[App\Http\Controllers\Admin\TeamController::class,'gaCas']);
    //Route::get('teams/show',[App\Http\Controllers\Admin\TeamController::class,'show']);
    Route::get('teams/edit/{id}',[App\Http\Controllers\Admin\TeamController::class,'edit']);
    Route::put('teams/update/{id}',[App\Http\Controllers\Admin\TeamController::class,'update']);
    Route::get('teams/show/{id}',[App\Http\Controllers\Admin\TeamController::class,'show']);
    Route::post('teams/{id}/toggleStatus',[App\Http\Controllers\Admin\TeamController::class,'toggleStatus']);
    // Team user routes
    Route::get('teams/user/create/{id}',[App\Http\Controllers\Admin\TeamUserController::class,'create']);
    Route::post('teams/user/store/{id}',[App\Http\Controllers\Admin\TeamUserController::class,'store']);

});