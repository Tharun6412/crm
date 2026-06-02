<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Dashboard\HomeController::class, 'index'])->name('home');
    Route::get('help', function(){
        return view('utils.help');
    });
    Route::resource('notifications', App\Http\Controllers\Admin\NotificationsController::class);
    Route::resource('user/exports', App\Http\Controllers\Admin\UserExportController::class);
    Route::resource('invoicesSet', App\Http\Controllers\InvoicesSetController::class);
    // PNGRB Application Controller
    // Route::get('pngrb/applications', [App\Http\Controllers\Pngrb\PngrbApplicationController::class, 'index']);
    Route::resource('pngrb/applications', App\Http\Controllers\Pngrb\PngrbApplicationController::class);
});