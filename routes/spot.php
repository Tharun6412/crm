<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Prospects 
Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::get('prospects/getIndustrialAreaByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getIndustrialAreaByGA']);
    Route::get('prospects/editStatus/{id}', [App\Http\Controllers\Spot\ProspectsController::class, 'editStatus']);
    Route::get('prospects/getSubStagesByStage', [App\Http\Controllers\Spot\ProspectsController::class, 'getSubStagesByStage']);
    Route::resource('prospects', App\Http\Controllers\Spot\ProspectsController::class);
    Route::get('prospectDocument/create/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'create']);
    Route::post('prospectDocument/store/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'store']);
    Route::resource('prospectDocument', App\Http\Controllers\Spot\ProspectDocumentController::class);
});
