<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth',ModuleAccess::class])->group(function() {
    Route::get('/',[App\Http\Controllers\Tickets\TicketController::class,'index']);
    Route::get('create/{id}',[App\Http\Controllers\Tickets\TicketController::class,'create']);
    Route::post('store/{id}',[App\Http\Controllers\Tickets\TicketController::class,'store']);
    Route::get('edit/{id}',[App\Http\Controllers\Tickets\TicketController::class,'edit']);
    Route::put('update/{id}',[App\Http\Controllers\Tickets\TicketController::class,'update']);
    Route::get('statusChange/{id}/{status_id}',[App\Http\Controllers\Tickets\TicketController::class,'statusChange']);
    Route::post('statusUpdate/{id}/{status_id}',[App\Http\Controllers\Tickets\TicketController::class,'statusUpdate']);
    Route::get('show/{id}',[App\Http\Controllers\Tickets\TicketController::class,'show']);
    Route::get('consumersTicket/{id}',[App\Http\Controllers\Tickets\TicketController::class,'consumerTicket']);
    Route::get('dashboard',[App\Http\Controllers\Tickets\DashboardController::class,'index']);
});