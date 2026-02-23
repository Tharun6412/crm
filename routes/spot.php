<?php

use App\Http\Middleware\RouteAccess;
use Illuminate\Support\Facades\Route;

// Prospects 
Route::middleware(['auth', RouteAccess::class])->group(function() {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Spot\DashboardController::class, 'index']);

    Route::get('prospects/getEditDetailsByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getEditDetailsByGA']);
    Route::get('prospects/getDetailsByGA', [App\Http\Controllers\Spot\ProspectsController::class, 'getDetailsByGA']);
    Route::post('prospects/updatePipeLine', [App\Http\Controllers\Spot\ProspectsController::class, 'updatePipeLine']);
    // Prospects 
    Route::get('prospects/prospectsExport', [App\Http\Controllers\Spot\ProspectsController::class, 'prospectsExport']);
    Route::resource('prospects', App\Http\Controllers\Spot\ProspectsController::class);
    // Prospect Pipeline
    Route::resource('prospect/pipeline', App\Http\Controllers\Spot\ProspectPipelineController::class);
    // Prospect Documents
    Route::get('prospectDocument/create/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'create']);
    Route::post('prospectDocument/store/{id}', [App\Http\Controllers\Spot\ProspectDocumentController::class, 'store']);
    Route::resource('prospectDocument', App\Http\Controllers\Spot\ProspectDocumentController::class);
    // Prospect Date Change Request
    Route::get('dateChangeRequest/create/{id}', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'create']);
    Route::post('dateChangeRequest/store/{id}', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'store']);
    Route::post('dateChangeRequest/approve', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'approve']);
    Route::post('dateChangeRequest/reject', [App\Http\Controllers\Spot\ProspectDateChangeRequestController::class, 'reject']);
    Route::resource('dateChangeRequest', App\Http\Controllers\Spot\ProspectDateChangeRequestController::class);
    // Comments
    Route::post('comments/store/{id}', [App\Http\Controllers\Spot\ProspectCommentsController::class, 'store']);
    Route::post('comments/destroy/{id}', [App\Http\Controllers\Spot\ProspectCommentsController::class, 'destroy']);
    Route::resource('comments', App\Http\Controllers\Spot\ProspectCommentsController::class);
    // Status change
    Route::get('prospectStatus/gaApprove/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'gaApprove']);
    Route::post('prospectStatus/gaHeadSubmit/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'gaHeadSubmit']);
    Route::get('prospectStatus/editStatus/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'editStatus']);
    Route::post('prospectStatus/updateStatus/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'updateStatus']);
    Route::get('prospectStatus/getSubStagesByStage', [App\Http\Controllers\Spot\ProspectStatusController::class, 'getSubStagesByStage']);
    Route::get('prospectStatus/getDetailsBySubStage', [App\Http\Controllers\Spot\ProspectStatusController::class, 'getDetailsBySubStage']);
    Route::get('prospectStatus/hold/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'hold']);
    Route::post('prospectStatus/updateHoldStatus/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'updateHoldStatus']);
    Route::put('prospectStatus/unHold/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'unHold']);
    Route::get('prospectStatus/cancel/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'cancel']);
    Route::post('prospectStatus/updateCancelStatus/{id}', [App\Http\Controllers\Spot\ProspectStatusController::class, 'updateCancelStatus']);
    Route::resource('prospectStatus', App\Http\Controllers\Spot\ProspectStatusController::class);
    // Dashboard
    Route::resource('dashboard', App\Http\Controllers\Spot\DashboardController::class);
    // Targets
    Route::post('targets/manageTargetData/{id}', [App\Http\Controllers\Spot\TargetsController::class, 'manageTargetData']);
    Route::resource('targets', App\Http\Controllers\Spot\TargetsController::class);
}); 
