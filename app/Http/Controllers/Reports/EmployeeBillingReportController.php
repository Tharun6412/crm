<?php

namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeBillingReportController extends Controller
{
    /**
     * List
     */
    public function index(Request $request)
    {
        // Get All GA
        $geo_areas = Ga::where('status', 1)->get();

        if($request->ajax()) {
            // Validation
            $request->validate([
                'ga_id' => 'required',
                'date_from' => 'required|date_format:d-m-Y',
                'date_to' => 'required|date_format:d-m-Y',
            ]);

            // Get Report
            // Employee collection
            $employee_bills = User::select('id', 'emp_id', 'first_name', 'last_name')
                ->whereHas('ga', fn($q) => $q->where('ga_id', $request->ga_id))
                ->whereHas('invoices.consumer', fn($q) => $q->where('ga_id', $request->ga_id))
                ->withCount(['invoices as invoice_count' => function ($q) use($request) {
                    $q->whereNot('status_id', InvoiceStatus::CANCEL->value)->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
                }])
                ->get();

            // Render output
            return view('reports.employee.bill-report.list-body', [
                'geo_areas' => $geo_areas,
                'employee_bills' => $employee_bills,
            ]);
        }

        // Render output
        return view('reports.employee.bill-report.list', ['geo_areas' => $geo_areas]);
    }
}