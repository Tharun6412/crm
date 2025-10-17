<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Prospects 
Route::middleware(['auth', RouteAccess::class])->group(function() {
    Route::get('prospects/getIndustrialAreaByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getIndustrialAreaByGA']);
    // Status Change Methods
    Route::get('prospects/editStatus/{id}', [App\Http\Controllers\Spot\ProspectsController::class, 'editStatus']);
    Route::post('prospects/updateStatus/{id}', [App\Http\Controllers\Spot\ProspectsController::class, 'updateStatus']);
    Route::post('prospects/updatePipeLine', [App\Http\Controllers\Spot\ProspectsController::class, 'updatePipeLine']);
    Route::get('prospects/getSubStagesByStage', [App\Http\Controllers\Spot\ProspectsController::class, 'getSubStagesByStage']);
    Route::get('prospects/getDetailsBySubStage', [App\Http\Controllers\Spot\ProspectsController::class, 'getDetailsBySubStage']);
    // Prospects 
    Route::resource('prospects', App\Http\Controllers\Spot\ProspectsController::class);
    // Prospect Documents
    Route::get('prospectDocument/create/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'create']);
    Route::post('prospectDocument/store/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'store']);
    Route::resource('prospectDocument', App\Http\Controllers\Spot\ProspectDocumentController::class);
    // Prospect Date Change Request
    Route::get('prospectDateChangeRequest/create/{id}', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'create']);
    Route::post('prospectDateChangeRequest/store/{id}', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'store']);
    Route::post('prospectDateChangeRequest/approve', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'approve']);
    Route::post('prospectDateChangeRequest/reject', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'reject']);
    Route::resource('prospectDateChangeRequest', App\Http\Controllers\Spot\ProspectDateChangeRequestController::class);
    // Comments
    Route::post('prospectComments/store/{id}', [App\Http\Controllers\Spot\ProspectCommentsController::class, 'store']);
    Route::post('prospectComments/destroy/{id}', [App\Http\Controllers\Spot\ProspectCommentsController::class, 'destroy']);
    Route::resource('prospectComments', App\Http\Controllers\Spot\ProspectCommentsController::class);
}); 
