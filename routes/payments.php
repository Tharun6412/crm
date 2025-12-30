<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Landing page
    Route::get('/', [App\Http\Controllers\Payments\GasPaymentsController::class, 'index']);

    // Gas Invoice
    // Invoice
    Route::prefix('gasPayments')->group(function () {
        Route::get('create/{consumer}', [App\Http\Controllers\Payments\GasPaymentsController::class, 'create']);
        Route::resource('/', App\Http\Controllers\Payments\GasPaymentsController::class);
    });

    // Invoice
    Route::prefix('invoicePayments')->group(function () {
        Route::get('create/{consumer}', [App\Http\Controllers\Payments\InvoicePaymentsController::class, 'create']);
        Route::get('show/{consumer}', [App\Http\Controllers\Payments\InvoicePaymentsController::class, 'show']);
        Route::resource('/', App\Http\Controllers\Payments\InvoicePaymentsController::class);
    });
    
});