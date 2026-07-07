<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;
//lead Routes
Route::middleware(['auth',ModuleAccess::class])->group(function(){
    Route::get('leads',[App\Http\Controllers\Lms\LeadController::class,'index']);
    Route::get('leads/create',[App\Http\Controllers\Lms\LeadController::class,'create']);
    Route::post('leads/store',[App\Http\Controllers\Lms\LeadController::class,'store']);
    Route::get('leads/show/{id}',[App\Http\Controllers\Lms\LeadController::class,'show']);
    Route::get('leads/statusChange/{id}',[App\Http\Controllers\Lms\LeadController::class,'statusChange']);
    Route::post('leads/statusUpdate/{id}',[App\Http\Controllers\Lms\LeadController::class,'statusUpdate']);
    Route::get('leads/getChildStatus',[App\Http\Controllers\Lms\LeadController::class, 'getChildStatus']);

    // Delivery Units
    Route::get('deliveryUnits/gaCas', [App\Http\Controllers\Lms\DeliveryUnitController::class, 'gaCas']);
    Route::get('deliveryUnits/getCaAreas', [App\Http\Controllers\Lms\DeliveryUnitController::class, 'getCaAreas']);
    Route::get('deliveryUnits/manageTeams/{id}', [App\Http\Controllers\Lms\DeliveryUnitController::class, 'manageTeams']);
    Route::post('deliveryUnits/updateDuTeams/{id}', [App\Http\Controllers\Lms\DeliveryUnitController::class, 'updateDuTeams']);
    Route::post('deliveryUnits/toggleStatus/{id}', [App\Http\Controllers\Lms\DeliveryUnitController::class, 'toggleStatus']);
    Route::resource('deliveryUnits', App\Http\Controllers\Lms\DeliveryUnitController::class);

    //Teams
    Route::get('teams',[App\Http\Controllers\Lms\TeamController::class,'index']);
    Route::get('teams/create',[App\Http\Controllers\Lms\TeamController::class,'create']);
    Route::post('teams/store',[App\Http\Controllers\Lms\TeamController::class,'store']);
    Route::get('teams/gaCas',[App\Http\Controllers\Lms\TeamController::class,'gaCas']);
    //Route::get('teams/show',[App\Http\Controllers\Admin\TeamController::class,'show']);
    Route::get('teams/edit/{id}',[App\Http\Controllers\Lms\TeamController::class,'edit']);
    Route::put('teams/update/{id}',[App\Http\Controllers\Lms\TeamController::class,'update']);
    Route::get('teams/show/{id}',[App\Http\Controllers\Lms\TeamController::class,'show']);
    Route::get('teams/getDeliveryUnits',[App\Http\Controllers\Lms\TeamController::class,'getDeliveryUnits']);
    Route::get('teams/getDeliveryUnitAreas', [App\Http\Controllers\Lms\TeamController::class, 'getDeliveryUnitAreas']);
    Route::post('teams/{id}/toggleStatus',[App\Http\Controllers\Lms\TeamController::class,'toggleStatus']);
    // Team user routes
    Route::get('teams/user/create/{id}',[App\Http\Controllers\Lms\TeamUserController::class,'create']);
    Route::post('teams/user/store/{id}',[App\Http\Controllers\Lms\TeamUserController::class,'store']);
    Route::get('teams/user/createUser',[App\Http\Controllers\Lms\TeamUserController::class,'createUser']);
    Route::post('teams/user/storeUser',[App\Http\Controllers\Lms\TeamUserController::class,'storeUser']);
    
});

