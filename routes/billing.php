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
        Route::get('/{id}', [App\Http\Controllers\Billing\GasBillController::class, 'show']);
        Route::resource('/', App\Http\Controllers\Billing\GasInvoiceController::class);
    });

    // Consumer Search
    Route::prefix('consumer')->group(function() {
        Route::get('search', [App\Http\Controllers\Billing\ConsumerSearchController::class, 'search']);
    });

    // Invoice
    Route::prefix('invoice')->group(function () {
        // Quick search
        Route::get('search', [App\Http\Controllers\Billing\InvoiceSearchController::class, 'search']);
        Route::get('cancel', [App\Http\Controllers\Billing\InvoiceSearchController::class, 'cancel']);
        Route::get('cancelInvoice/{id}', [App\Http\Controllers\Billing\InvoiceSearchController::class, 'cancelInvoice']);
        Route::put('cancelInvoiceUpdate/{id}', [App\Http\Controllers\Billing\InvoiceSearchController::class, 'cancelInvoiceUpdate']);

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
        Route::get('showCreditByConsumerId/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'showCreditByConsumerId']);
        Route::get('/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'show']);
        Route::get('create/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'index']);
        Route::post('create/{id}', [App\Http\Controllers\Billing\CreditNoteController::class, 'store']);
    });
    
    // Ledger
    Route::resource('ledger', App\Http\Controllers\Billing\LedgerController::class);
});