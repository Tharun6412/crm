<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    Route::resource('applications', App\Http\Controllers\Pngrb\PngrbApplicationController::class);
});