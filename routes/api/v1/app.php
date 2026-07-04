<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Common services
    Route::get('gaDistricts', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'gaDistricts']);
    Route::get('gaDistrictsSchemes', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'gaDistrictsSchemes']);
    Route::get('districtCas', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'districtCas']);
    Route::get('caAreas', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'caAreas']);
    Route::get('schemeDetails', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'schemeDetails']);
    Route::get('getSubCategories', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'getSubCategories']);
    Route::get('gaSchemesByType', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'gaSchemesByType']);
    Route::get('getSubAreasList', [App\Http\Controllers\Api\V1\Common\CommonController::class, 'getSubAreasList']);

    // Dashboard
    Route::get('dashboard/list', [App\Http\Controllers\Api\V1\Application\DashboardController::class, 'list']);
    Route::get('myActivity', [App\Http\Controllers\Api\V1\Application\MyActivityController::class, 'index']);
    Route::get('consumerTeams', [App\Http\Controllers\Api\V1\Application\ConsumerTeamController::class, 'index']);
    Route::get('consumerTeams/assignTeam/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerTeamController::class, 'assignTeam']);
    Route::post('consumerTeams/updateTeamAssignment/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerTeamController::class, 'updateTeamAssignment']);
    // Consumers routes
    Route::prefix('consumer')->group(function () {
        // Consumer Controller
        Route::get('list', [App\Http\Controllers\Api\V1\Application\ConsumerController::class, 'list']);
        Route::get('details/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerController::class, 'details']);
        // Registration
        Route::post('lpgUpdate/{id}',[App\Http\Controllers\Api\V1\Application\ConsumerRegistrationController::class,'lpgUpdate']);
        Route::get('create', [App\Http\Controllers\Api\V1\Application\ConsumerRegistrationController::class, 'create']);
        Route::post('store', [App\Http\Controllers\Api\V1\Application\ConsumerRegistrationController::class, 'store']);
        Route::post('updateSubArea/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerRegistrationController::class, 'updateSubArea']);
        // Onboarding Activity
        Route::post('acceptance/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOnboardingController::class, 'acceptance'])->whereNumber('id');
        Route::post('execution/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOnboardingController::class, 'execution'])->whereNumber('id');
        Route::post('hscConnect/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOnboardingController::class, 'hscConnect'])->whereNumber('id');
        Route::get('getActivate/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOnboardingController::class, 'getActivate'])->whereNumber('id');
        Route::post('activate/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOnboardingController::class, 'activate'])->whereNumber('id');
        // Operations
        Route::post('tdisconnect/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOperationsController::class, 'tdisconnect'])->whereNumber('id');
        Route::post('pdisconnect/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOperationsController::class, 'pdisconnect'])->whereNumber('id');
        Route::post('payDeposit/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOperationsController::class, 'payDeposit'])->whereNumber('id');
        Route::post('refundRequest/{id}', [App\Http\Controllers\Api\V1\Application\ConsumerOperationsController::class, 'refundRequest'])->whereNumber('id');
    });
    // Complaints routes
    Route::prefix('complaint')->group(function() {
        Route::get('list/{id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'list']);
        Route::get('show/{id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'show']);
        Route::get('create', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'create']);
        Route::post('store/{id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'store']);
        Route::post('statusChange/{id}/{status_id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'statusChange']);
        Route::post('closeOTP/{id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'closeOTP']);
        Route::post('closeComplaint/{id}/{status_id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'closeComplaint']);
        Route::post('comments/{id}/', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'comments']);
        Route::post('resendOTP/{id}', [App\Http\Controllers\Api\V1\Application\ComplaintsController::class, 'resendOTP']);
    });
    
    Route::prefix('bills')->group(function() {
        // Gas Bills Generation
        Route::post('generateGasBill/{id}', [App\Http\Controllers\Api\V1\Application\BillingController::class, 'generateGasBill'])->whereNumber('id');
        Route::post('storeGasBill/{id}', [App\Http\Controllers\Api\V1\Application\BillingController::class, 'storeGasBill'])->whereNumber('id');
    });

    // Invoices List
    Route::prefix('invoices')->group(function() {
        Route::get('list/{consumer_id}', [App\Http\Controllers\Api\V1\Application\InvoiceController::class, 'list']);
        Route::get('viewInvoice/{id}', [App\Http\Controllers\Api\V1\Application\InvoiceController::class, 'viewInvoice'])->whereNumber('id');
        // Gas Bills List
        Route::get('gasBills/{consumer_id}', [App\Http\Controllers\Api\V1\Application\InvoiceController::class, 'gasBills']);
        Route::get('viewGasBill/{id}', [App\Http\Controllers\Api\V1\Application\InvoiceController::class, 'viewGasBill'])->whereNumber('id');
    });
    
    // Payments
    Route::prefix('payments')->group(function() {
        Route::get('list', [App\Http\Controllers\Api\V1\Application\PaymentsController::class, 'list']);
        // Route::get('edit/{id}', [App\Http\Controllers\Api\V1\Application\PaymentsController::class,'edit']);
        Route::post('update', [App\Http\Controllers\Api\V1\Application\PaymentsController::class,'update']);
    });

    //Tickets
    Route::prefix('tickets')->group(function() {
        Route::get('create',[App\Http\Controllers\Api\V1\Application\TicketController::class,'create']);
        Route::post('store/{consumer_id}',[App\Http\Controllers\Api\V1\Application\TicketController::class,'store']);
        Route::get('list/{id}',[App\Http\Controllers\Api\V1\Application\TicketController::class,'list']);
        Route::get('show/{id}',[App\Http\Controllers\Api\V1\Application\TicketController::class,'show']);
    }); 
});