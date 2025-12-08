<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Registration Controller
    Route::resource('register/domestic', App\Http\Controllers\Consumer\RegistrationController::class);
    // Onboarding activites
    Route::resource('trPayment', App\Http\Controllers\Consumer\TRPaymentController::class);
    Route::resource('accept', App\Http\Controllers\Consumer\AcceptController::class);
    Route::resource('execute', App\Http\Controllers\Consumer\ExecuteController::class);
    Route::resource('hsconnect', App\Http\Controllers\Consumer\HscController::class);
    Route::resource('activate', App\Http\Controllers\Consumer\ActivateController::class);
    Route::resource('tdisconnect', App\Http\Controllers\Consumer\TemporaryDisconnectController::class);
    Route::resource('pdisconnect', App\Http\Controllers\Consumer\PermanentDisconnectController::class);
    Route::resource('refund', App\Http\Controllers\Consumer\RefundController::class);
    Route::resource('payDeposit', App\Http\Controllers\Consumer\PayDepositController::class);
    Route::resource('reconnect', App\Http\Controllers\Consumer\ReconnectController::class);
    
    // Consumers list
    Route::resource('tr', App\Http\Controllers\Consumer\TRController::class);
    Route::get('/{id}', [App\Http\Controllers\Consumer\ConsumerController::class, 'show'])->whereNumber('id');
    Route::get('/{status:slug?}', [App\Http\Controllers\Consumer\ConsumerController::class, 'index']); 
});