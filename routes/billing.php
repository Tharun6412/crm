<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Gas Invoice
    Route::resource('gasInvoice', App\Http\Controllers\Billing\GasInvoiceController::class);
    Route::get('gasInvoice/create/{consumer}', [App\Http\Controllers\Billing\GasInvoiceController::class, 'create']);

    // Invoice
    Route::prefix('invoice')->group(function () {
        Route::get('create/{id}', [App\Http\Controllers\Billing\InvoiceController::class, 'create']);
        Route::get('createBody', [App\Http\Controllers\Billing\InvoiceController::class, 'createBody']);
        Route::get('typeItems', [App\Http\Controllers\Billing\InvoiceController::class, 'typeItems']);
        Route::post('addItem', [App\Http\Controllers\Billing\InvoiceController::class, 'addItem']);
        Route::delete('removeItem', [App\Http\Controllers\Billing\InvoiceController::class, 'removeItem']);
        Route::post('/{id}', [App\Http\Controllers\Billing\InvoiceController::class, 'store']);
    });
    
    // Credit note
    Route::prefix('creditNote')->group(function () {
        // 
    });
    
});