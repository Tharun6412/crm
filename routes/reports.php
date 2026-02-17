<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Reports\ReportController::class, 'index']);
Route::get('consumer/onboarding', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'index']);
Route::get('consumer/onboarding/activity', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'activity']);
Route::get('consumer/onboardingStatusReport', [App\Http\Controllers\Reports\OnboardingStatusReportController::class, 'index']);

// SD Reports
Route::get('consumer/sdReport', [App\Http\Controllers\Reports\SDReportController::class, 'index']);
Route::get('consumer/sdDetails', [App\Http\Controllers\Reports\SDReportController::class, 'sdDetails']);
Route::get('consumer/sdReportExport', [App\Http\Controllers\Reports\SDReportController::class, 'sdReportExport']);
Route::get('ageingReport', [App\Http\Controllers\Reports\AgeingReportController::class, 'index']);
Route::get('ageingReport/invoicesList', [App\Http\Controllers\Reports\AgeingReportController::class, 'invoicesList']);
Route::get('ageingReport/agingInvoicesExport', [App\Http\Controllers\Reports\AgeingReportController::class, 'agingInvoicesExport']);
Route::get('gasSaleReport', [App\Http\Controllers\Reports\GasSaleReportController::class, 'index']);
Route::get('invoiceReport', [App\Http\Controllers\Reports\InvoicesReportController::class, 'index']);
