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
});

