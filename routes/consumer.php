<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function() {
    // Consumers list
    Route::get('/', [App\Http\Controllers\Consumer\ConsumerController::class, 'index']);
    
    // Registration Controller
    Route::get('register/getCasByDistrict', [App\Http\Controllers\Consumer\RegistrationController::class, 'getCasByDistrict']);
    Route::get('register/getDistrictsByGa', [App\Http\Controllers\Consumer\RegistrationController::class, 'getDistrictsByGa']);
    Route::get('register/getConsumerDepositDetails/{id}', [App\Http\Controllers\Consumer\RegistrationController::class, 'getConsumerDepositDetails']);
    Route::post('register/payDeposit/{id}', [App\Http\Controllers\Consumer\RegistrationController::class, 'payDeposit']);
    Route::resource('register', App\Http\Controllers\Consumer\RegistrationController::class);
    
    // Consumer Controller
    Route::resource('tr', App\Http\Controllers\Consumer\TRController::class);    
});