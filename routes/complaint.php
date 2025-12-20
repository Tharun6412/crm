<?php
/**
 * Complaint Routes
 */

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    Route::get('getSubCategoryDetails',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategoryDetails']);
    Route::get('getSubCategories',[App\Http\Controllers\Complaints\ComplaintsController::class, 'getSubCategories']);
    Route::get('create/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'create']);
    Route::post('store/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'store']);
    Route::get('/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'show'])->whereNumber('id');
    Route::get('assign/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assign']);
    Route::get('inProgress/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'inProgress']);
    Route::get('investigate/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'investigate']);
    Route::get('close/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'close']);
    Route::post('assignTo/{id}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'assignTo']);
    Route::post('statusChange/{id}/{status}',[App\Http\Controllers\Complaints\ComplaintsController::class, 'statusChange']);
    Route::resource('/', App\Http\Controllers\Complaints\ComplaintsController::class);
});