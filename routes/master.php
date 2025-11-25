<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
// Prospects 
Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::prefix('consumer')->group(function () {
        Route::resource('/schemes', App\Http\Controllers\Master\Consumer\SchemesController::class);
    });
});