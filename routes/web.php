<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Dashboard\HomeController::class, 'index'])->name('home');
});


