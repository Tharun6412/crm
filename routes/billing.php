<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Landing page
    Route::get('/', [App\Http\Controllers\Billing\InvoiceController::class, 'index']);

    // Gas Invoice
    // Invoice
    Route::prefix('gasInvoice')->group(function () {
        Route::get('create/{consumer}', [App\Http\Controllers\Billing\GasInvoiceController::class, 'create']);
        Route::resource('/', App\Http\Controllers\Billing\GasInvoiceController::class);
    });

    // Invoice
    Route::prefix('invoice')->group(function () {
        Route::get('create/{id}', [App\Http\Controllers\Billing\InvoiceController::class, 'create']);
        Route::get('createBody', [App\Http\Controllers\Billing\InvoiceController::class, 'createBody']);
        Route::get('typeItems', [App\Http\Controllers\Billing\InvoiceController::class, 'typeItems']);
        Route::post('addItem', [App\Http\Controllers\Billing\InvoiceController::class, 'addItem']);
        Route::delete('removeItem', [App\Http\Controllers\Billing\InvoiceController::class, 'removeItem']);
        Route::get('/{id}', [App\Http\Controllers\Billing\InvoiceController::class, 'show']);
        Route::post('/{id}', [App\Http\Controllers\Billing\InvoiceController::class, 'store']);
        Route::get('create', [App\Http\Controllers\Billing\InvoiceController::class, 'index']);
        Route::get('/', [App\Http\Controllers\Billing\InvoiceController::class, 'index']);
    });
    
    // Credit note
    Route::prefix('creditNote')->group(function () {
        Route::get('/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'show']);
        Route::get('create/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'index']);
        Route::post('create/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'store']);
    });
    
});