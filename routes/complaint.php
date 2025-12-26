<?php
/**
 * Complaint Routes
 */

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Additional methods
    Route::get('calls/getSubCategoryDetails',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategoryDetails']);
    Route::get('calls/getSubCategories',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategories']);
    Route::get('calls/consumerComplaints/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'consumerComplaints']);
    // Comments
    Route::post('calls/comments/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'comments']);
    Route::post('calls/deleteComment/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'deleteComment']);
    // Basic CRUD operations
    Route::get('calls/create/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'create']);
    Route::post('calls/store/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'store']);
    Route::get('calls/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'show'])->whereNumber('id');
    Route::get('calls/{id}/edit',[App\Http\Controllers\Complaints\ComplaintsController::class, 'edit']);
    Route::post('calls/update/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'update']);
    // Status Change Routes
    Route::get('calls/assign/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assign']);
    Route::get('calls/inProgress/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'inProgress']);
    Route::get('calls/investigate/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'investigate']);
    Route::get('calls/close/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'close']);
    Route::get('calls/cancel/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'cancel']);
    Route::post('calls/assignTo/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assignTo']);
    Route::post('calls/statusChange/{id}/{status}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'statusChange']);
    // resource
    Route::resource('calls/feedback', App\Http\Controllers\Complaints\FeedbackController::class);
    Route::resource('calls', App\Http\Controllers\Complaints\ComplaintsController::class);
    // External Calls
    Route::resource('externalCalls', App\Http\Controllers\Complaints\ExternalCallsController::class);
});