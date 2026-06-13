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
    Route::get('consumer/onboarding/getCumulativeConsumerStatusCount', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'getCumulativeConsumerStatusCount']);
    Route::get('consumer/onboarding/getDistrictsOverviewCount', [App\Http\Controllers\Reports\ConsumerOnboardingReportController::class, 'getDistrictsOverviewCount']);
    Route::get('consumer/onboardingStatusReport', [App\Http\Controllers\Reports\OnboardingStatusReportController::class, 'index']);
    Route::get('consumer/onboardingStatusReport/consumerOnboardExport', [App\Http\Controllers\Reports\OnboardingStatusReportController::class, 'consumerOnboardExport']);
    Route::get('consumer/conversions', [App\Http\Controllers\Reports\ConsumerConversionController::class, 'index']);
    Route::get('consumer/conversions/prepaidConsumers', [App\Http\Controllers\Reports\ConsumerConversionController::class, 'prepaidConsumers']);
    Route::get('consumer/conversions/getPrepaidCountByDistricts', [App\Http\Controllers\Reports\ConsumerConversionController::class, 'getPrepaidCountByDistricts']);
    Route::get('consumer/conversions/consumerPrepaidExport', [App\Http\Controllers\Reports\ConsumerConversionController::class, 'consumerPrepaidExport']);

    // SD Reports
    Route::get('consumer/sdReport', [App\Http\Controllers\Reports\SDReportController::class, 'index']);
    Route::get('consumer/sdDetails', [App\Http\Controllers\Reports\SDReportController::class, 'sdDetails']);
    Route::get('consumer/sdDetails-counts', [App\Http\Controllers\Reports\SDReportController::class, 'sdDetailsCount']);
    Route::get('consumer/sdReportExport', [App\Http\Controllers\Reports\SDReportController::class, 'sdReportExport']);
    Route::get('ageingReport', [App\Http\Controllers\Reports\AgeingReportController::class, 'index']);
    // Route::get('ageingReport/invoicesList', [App\Http\Controllers\Reports\AgeingReportController::class, 'invoicesList']);
    Route::get('gasSaleReport', [App\Http\Controllers\Reports\GasSaleReportController::class, 'index']);
    // Invoice report
    Route::get('invoices/all', [App\Http\Controllers\Reports\InvoicesReportController::class, 'index']);
    Route::get('invoices/all-counts', [App\Http\Controllers\Reports\InvoicesReportController::class, 'reportCounts']);
    Route::get('invoiceReport/getInvoicesExport', [App\Http\Controllers\Reports\InvoicesReportController::class, 'getInvoicesExport']);
    Route::get('invoiceReport/invoicesReportExport', [App\Http\Controllers\Reports\InvoicesReportController::class, 'invoicesReportExport']);
    Route::get('invoices/list', [App\Http\Controllers\Reports\InvoicesReportController::class, 'list']);
    Route::get('invoices/list-counts', [App\Http\Controllers\Reports\InvoicesReportController::class, 'listCounts']);

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

    // Invoice report
    Route::get('unbilled', [App\Http\Controllers\Reports\UnbilledReport::class, 'index']);
    // Route::get('invoices/all-counts', [App\Http\Controllers\Reports\InvoicesReportController::class, 'reportCounts']);
    Route::get('unbilled/listExport', [App\Http\Controllers\Reports\UnbilledReport::class, 'listExport']);
    Route::get('unbilled/list', [App\Http\Controllers\Reports\UnbilledReport::class, 'list']);

    // Waiting Report
    Route::get('consumer/connectionProgress', [App\Http\Controllers\Reports\ConsumerWaitingController::class, 'index']);
    Route::get('consumer/connectionProgress/ageingProgress', [App\Http\Controllers\Reports\ConsumerWaitingController::class, 'ageingProgress']);
    Route::get('consumer/waiting/consumersListForEmployees', [App\Http\Controllers\Reports\ConsumerWaitingController::class, 'consumersListForEmployees']);
    Route::get('consumer/waiting/getAreasList',[App\Http\Controllers\Reports\ConsumerWaitingController::class,'getAreasList']);
    Route::get('consumer/activity',[App\Http\Controllers\Reports\ActivityController::class,'activity']);
    Route::get('consumer/employee/activity',[App\Http\Controllers\Reports\EmployeeActivityController::class,'index']);
});
