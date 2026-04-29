<?php

use App\Http\Middleware\ModuleAccess;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', ModuleAccess::class])->group(function() {
    //Dashboard list
    Route::get('/', [App\Http\Controllers\Reports\ReportController::class, 'index']);
    Route::get('consumer/onboarding', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'index']);
    Route::get('consumer/onboarding/getCountByDistricts', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'getCountByDistricts']);
    Route::get('consumer/onboarding/getActivatedCountByDistricts', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'getActivatedCountByDistricts']);
    Route::get('consumer/onboarding/activity', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'activity']);
    Route::get('consumer/onboardingStatusReport', [App\Http\Controllers\Reports\OnboardingStatusReportController::class, 'index']);
    Route::get('consumer/onboardingStatusReport/consumerOnboardExport', [App\Http\Controllers\Reports\OnboardingStatusReportController::class, 'consumerOnboardExport']);

    // SD Reports
    Route::get('consumer/sdReport', [App\Http\Controllers\Reports\SDReportController::class, 'index']);
    Route::get('consumer/sdDetails', [App\Http\Controllers\Reports\SDReportController::class, 'sdDetails']);
    Route::get('consumer/sdReportExport', [App\Http\Controllers\Reports\SDReportController::class, 'sdReportExport']);
    Route::get('ageingReport', [App\Http\Controllers\Reports\AgeingReportController::class, 'index']);
    // Route::get('ageingReport/invoicesList', [App\Http\Controllers\Reports\AgeingReportController::class, 'invoicesList']);
    Route::get('gasSaleReport', [App\Http\Controllers\Reports\GasSaleReportController::class, 'index']);
    // Invoice report
    Route::get('invoices/all', [App\Http\Controllers\Reports\InvoicesReportController::class, 'index']);
    Route::get('invoices/all-counts', [App\Http\Controllers\Reports\InvoicesReportController::class, 'reportCounts']);
    Route::get('invoiceReport/invoicesReportExport', [App\Http\Controllers\Reports\InvoicesReportController::class, 'invoicesReportExport']);
    Route::get('invoices/list', [App\Http\Controllers\Reports\InvoicesReportController::class, 'list']);

    Route::get('consumer/consumerAgeingReport', [App\Http\Controllers\Reports\Consumer\ConsumerAgeingReport::class, 'index']);
    Route::get('consumer/consumerAgeingReport/consumersList', [App\Http\Controllers\Reports\Consumer\ConsumerAgeingReport::class, 'consumersList']);
    Route::get('consumer/consumerAgeingReport/consumerExport', [App\Http\Controllers\Reports\Consumer\ConsumerAgeingReport::class, 'consumerExport']);

    // Refund Reports
    Route::get('consumer/refundReportExport', [App\Http\Controllers\Reports\RefundReportController::class, 'refundReportExport']);
    Route::get('consumer/refundReport', [App\Http\Controllers\Reports\RefundReportController::class, 'index']);
    // Employee Collection Report
    Route::get('employee/collection', [App\Http\Controllers\Reports\EmployeeCollectionReport::class, 'index']);
    Route::get('employee/collection/details', [App\Http\Controllers\Reports\EmployeeCollectionReport::class, 'show']);
    // Refund Reports
    Route::get('consumer/refundReportExport', [App\Http\Controllers\Reports\RefundReportController::class, 'refundReportExport']);
    Route::get('consumer/refundReport', [App\Http\Controllers\Reports\RefundReportController::class, 'index']);
    // Employee Collection Report
    Route::get('employeeCollectionReport', [App\Http\Controllers\Reports\EmployeeCollectionReport::class, 'index']);
    // Payments Report
    Route::get('paymentsReport', [App\Http\Controllers\Reports\PaymentsReportController::class, 'index']);
    Route::get('paymentsReport/paymentsReportExport', [App\Http\Controllers\Reports\PaymentsReportController::class, 'paymentsReportExport']);
    // GA Wise Recharge Report
    Route::get('consumer/recharge' , [App\Http\Controllers\Reports\Consumer\RechargeReportController::class, 'index']);
    Route::get('consumer/recharge/List' , [App\Http\Controllers\Reports\Consumer\RechargeReportController::class, 'rechargesList']);
});
