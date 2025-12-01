<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    // Consumers list
    Route::resource('tr', App\Http\Controllers\Consumer\TRController::class);
    Route::resource('trPayment', App\Http\Controllers\Consumer\TRPaymentController::class);
    
    // Registration Controller
    Route::get('register/getCasByDistrict', [App\Http\Controllers\Consumer\RegistrationController::class, 'getCasByDistrict']);
    Route::get('register/getDistrictsByGa', [App\Http\Controllers\Consumer\RegistrationController::class, 'getDistrictsByGa']);
    Route::get('register/getSchemeDetailsBySchemeId', [App\Http\Controllers\Consumer\RegistrationController::class, 'getSchemeDetailsBySchemeId']);
    Route::get('register/getConsumerDepositDetails/{id}', [App\Http\Controllers\Consumer\RegistrationController::class, 'getConsumerDepositDetails']);
    Route::post('register/payDeposit/{id}', [App\Http\Controllers\Consumer\RegistrationController::class, 'payDeposit']);
    Route::resource('register', App\Http\Controllers\Consumer\RegistrationController::class);
    Route::resource('registration', App\Http\Controllers\Consumer\AcceptController::class);
    Route::resource('execution', App\Http\Controllers\Consumer\ExecuteController::class);
    Route::resource('hscAction', App\Http\Controllers\Consumer\HscController::class);
    Route::resource('activation', App\Http\Controllers\Consumer\ActivateController::class);
    Route::resource('temporaryDisconnect', App\Http\Controllers\Consumer\TemporaryDisconnectController::class);
    Route::resource('permanentDisconnect', App\Http\Controllers\Consumer\PermanentDisconnectController::class);
    Route::resource('refund', App\Http\Controllers\Consumer\RefundController::class);

    Route::get('/{id}', [App\Http\Controllers\Consumer\ConsumerController::class, 'show'])->whereNumber('id');
    Route::get('/{status:slug?}', [App\Http\Controllers\Consumer\ConsumerController::class, 'index']); 
});