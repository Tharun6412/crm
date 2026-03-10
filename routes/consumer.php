<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Registration Controller
    Route::resource('register/domestic', App\Http\Controllers\Consumer\RegistrationController::class);
    Route::resource('register/commercial', App\Http\Controllers\Consumer\CommercialRegistrationController::class);
    Route::resource('register/industrial', App\Http\Controllers\Consumer\IndustrialRegistrationController::class);
    // Onboarding activites
    Route::resource('trPayment', App\Http\Controllers\Consumer\TRPaymentController::class);
    Route::resource('accept', App\Http\Controllers\Consumer\AcceptController::class);
    Route::resource('execute', App\Http\Controllers\Consumer\ExecuteController::class);
    Route::resource('hsconnect', App\Http\Controllers\Consumer\HscController::class);
    Route::resource('activate', App\Http\Controllers\Consumer\ActivateController::class);
    Route::resource('tdisconnect', App\Http\Controllers\Consumer\TemporaryDisconnectController::class);
    Route::resource('pdisconnect', App\Http\Controllers\Consumer\PermanentDisconnectController::class);
    Route::resource('prepaid', App\Http\Controllers\Consumer\PrepaidConsumerController::class);
    
    // Search
    Route::get('search', [App\Http\Controllers\Consumer\ConsumerSearchController::class, 'search']);
    
    // Deposit
    Route::resource('payDeposit', App\Http\Controllers\Consumer\PayDepositController::class);
    
    // Reconnect
    Route::resource('reconnect', App\Http\Controllers\Consumer\ReconnectController::class);
    
    // Consumer invoices
    Route::get('invoices/{id}/{type}', [App\Http\Controllers\Consumer\ConsumerInvoiceController::class, 'index']);
    
    // Refunds
    Route::get('refunds/refundRequest/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'refundRequest']);
    Route::post('refunds/refundRequestUpdate/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'refundRequestUpdate']);
    Route::get('refunds/process/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'process']);
    Route::post('refunds/processUpdate/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'processUpdate']);
    Route::get('refunds/approve/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'approve']);
    Route::post('refunds/approveUpdate/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'approveUpdate']);
    Route::get('refunds/close/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'close']);
    Route::post('refunds/closeRefund/{id}',[App\Http\Controllers\Consumer\RefundController::class, 'closeRefund']);
    Route::resource('refunds', App\Http\Controllers\Consumer\RefundController::class);
    
    // Consumer Meter Change 
    Route::resource('meterChange', App\Http\Controllers\Consumer\MeterChangeController::class);
    
    // Consumers Filter
    Route::get('filters/areaByCA', [App\Http\Controllers\Consumer\ConsumerFilterController::class, 'areaByCA']);
    Route::resource('filters', App\Http\Controllers\Consumer\ConsumerFilterController::class);
    // Consumers list
    Route::get('consumerExport', [App\Http\Controllers\Consumer\ConsumerController::class, 'consumerExport']);
    Route::get('consumerDocs/{id}', [App\Http\Controllers\Consumer\ConsumerController::class, 'consumerDocs'])->whereNumber('id');
    Route::get('/{id}', [App\Http\Controllers\Consumer\ConsumerController::class, 'show'])->whereNumber('id');
    Route::get('/{status:slug?}', [App\Http\Controllers\Consumer\ConsumerController::class, 'index']);

    // Conversion
    Route::resource('conversion', App\Http\Controllers\prepaid\ConversionController::class);
    
    // Prepaid consumer send to HES
    Route::get('prepaid/sendToHes/{id}', [App\Http\Controllers\Consumer\PrepaidConsumerController::class, 'sendToHes']);
    Route::put('prepaid/hesSubmit/{id}', [App\Http\Controllers\Consumer\PrepaidConsumerController::class, 'hesSubmit']);
    Route::get('prepaid/consumerRechargeList/{id}', [App\Http\Controllers\Consumer\PrepaidConsumerController::class, 'consumerRechargeList']);
    Route::get('prepaid/balance/{id}', [App\Http\Controllers\Consumer\ConsumerPrepaidBalanceController::class, 'prepaidBalance'])->middleware('throttle:balance-check');
});