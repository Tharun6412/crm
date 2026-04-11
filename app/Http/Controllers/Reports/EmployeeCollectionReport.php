<?php
namespace App\Http\Controllers\Reports;

use App\Enums\PaymentStatus;
USE App\Enums\SDPaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Consumer\ConsumerSdPayment;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Node\Query\AndExpr;

class EmployeeCollectionReport extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $geo_areas = Ga::all();

        if($request->ajax()) {
            // Validation
            $request->validate([
                'ga_id' => 'required',
                'date_from' => 'required|date_format:d-m-Y',
                'date_to' => 'required|date_format:d-m-Y',
            ]);

            // Employee collection
            $employee_collection = User::select('id', 'emp_id', 'first_name', 'last_name')
                ->whereHas('ga', fn($q) => $q->where('ga_id', $request->ga_id))
                ->withSum(['invoicePayments as invoice_collection' => function ($q) use($request) {
                    $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
                }], 'amount')
                ->withSum(['sdPayments as sd_collection' => function($q) use($request) {
                    $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
                }], 'amount')
                ->having('invoice_collection', '>', 0)
                ->orHaving('sd_collection', '>', 0)
                ->get();
                
            // Render output
            return view('reports.employee.collection-report.list-body', ['employee_collection' => $employee_collection, 'geo_areas' => $geo_areas]);
        }
        // Render output
        return view('reports.employee.collection-report.list', ['geo_areas' => $geo_areas]);
    }
}