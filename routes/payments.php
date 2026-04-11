<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Landing page
    Route::get('/', [App\Http\Controllers\Payments\GasPaymentsController::class, 'index']);
    // Payment Search
    // Route::get('search', [App\Http\Controllers\Payments\PaymentsSearchController::class, 'search']);
    Route::get('reversal', [App\Http\Controllers\Payments\PaymentsSearchController::class, 'reversal']);
    Route::get('reversalPayment/{id}', [App\Http\Controllers\Payments\PaymentsSearchController::class, 'reversalPayment']);
    Route::put('reversalPaymentUpdate/{id}', [App\Http\Controllers\Payments\PaymentsSearchController::class, 'reversalPaymentUpdate']);
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
        Route::get('receipt/{id}', [App\Http\Controllers\Payments\InvoicePaymentsController::class, 'receipt']);
        Route::resource('/', App\Http\Controllers\Payments\InvoicePaymentsController::class);
    });
    
    // Online transactions
    Route::resource('transactions', App\Http\Controllers\Payments\TransactionsController::class);
});