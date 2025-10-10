<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('prospects')->group(function() {
    Route::get('getIndustrialAreaByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getIndustrialAreaByGA']);
    Route::resource('', App\Http\Controllers\Spot\ProspectsController::class);
});