<?php
/**
 * Complaint Routes
 */

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Complaints\DashboardController::class, 'index']);
    // Additional methods
    Route::get('getSubCategoryDetails',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategoryDetails']);
    Route::get('getSubCategories',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategories']);
    Route::get('consumerComplaints/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'consumerComplaints']);
    // Comments
    Route::post('comments/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'comments']);
    Route::post('deleteComment/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'deleteComment']);
    // Basic CRUD operations
    Route::get('create/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'create']);
    Route::post('store/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'store']);
    Route::get('{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'show'])->whereNumber('id');
    Route::get('{id}/edit',[App\Http\Controllers\Complaints\ComplaintsController::class, 'edit']);
    Route::post('update/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'update']);
    Route::post('deleteComplaintDocument/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'deleteComplaintDocument']);

    // Status Change Routes
    Route::get('usersListByDepartment',[App\Http\Controllers\Complaints\ComplaintsController::class, 'usersListByDepartment']);
    Route::get('assign/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assign']);
    Route::get('inProgress/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'inProgress']);
    Route::get('investigate/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'investigate']);
    Route::get('close/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'close']);
    Route::get('cancel/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'cancel']);
    Route::post('assignTo/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assignTo']);
    Route::post('statusChange/{id}/{status}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'statusChange']);
    Route::post('closeComplaint/{id}/{status}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'closeComplaint']);
    // OTP
    Route::post('closeOTP',[App\Http\Controllers\Complaints\ComplaintsController::class, 'closeOTP']);
    Route::post('resendOTP',[App\Http\Controllers\Complaints\ComplaintsController::class, 'resendOTP']);
    // resource
    Route::resource('feedback', App\Http\Controllers\Complaints\FeedbackController::class);
    // Export
    Route::get('complaintExport',[App\Http\Controllers\Complaints\ComplaintsController::class, 'complaintExport']);
    Route::resource('', App\Http\Controllers\Complaints\ComplaintsController::class);
    // Consumer Call search
    Route::get('search',[App\Http\Controllers\Complaints\ConsumerSearchController::class, 'search']);
    // External Calls
    Route::resource('external', App\Http\Controllers\Complaints\ExternalCallsController::class);
    // Reports
    Route::get('reports/complaints',[App\Http\Controllers\Complaints\ReportsController::class, 'complaints']);
    Route::get('reports/gaReport',[App\Http\Controllers\Complaints\ReportsController::class, 'gaReport']);
    Route::get('reports/categoryReport',[App\Http\Controllers\Complaints\ReportsController::class, 'categoryReport']);
    Route::get('reports/deviationReport',[App\Http\Controllers\Complaints\ReportsController::class, 'deviationReport']);
    // Calls
    Route::resource('displayOtp',App\Http\Controllers\Complaints\complaintOtpController::class);
});