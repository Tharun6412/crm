<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Prospects 
Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::get('prospects/getIndustrialAreaByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getIndustrialAreaByGA']);
    Route::resource('prospects', App\Http\Controllers\Spot\ProspectsController::class);
});
