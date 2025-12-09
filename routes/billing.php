<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Gas Invoice Controller
    Route::resource('gasInvoice', App\Http\Controllers\Billing\GasInvoiceController::class);
    Route::get('gasInvoice/create/{consumer}', [App\Http\Controllers\Billing\GasInvoiceController::class, 'create'])
     ->name('gasInvoice.create.withConsumer');
});