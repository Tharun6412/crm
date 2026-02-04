<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Reports\ReportController::class, 'index']);
Route::get('consumer/onboarding', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'index']);
Route::get('consumer/onboarding/activity', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'activity']);