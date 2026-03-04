<?php
namespace App\Http\Controllers\Reports;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Invoice\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeCollectionReport extends Controller
{
    /**
     * Index method
     */
    public function index(Request $request)
    {
        $payments_list = InvoicePayment::select('created_by', DB::raw('SUM(amount) as total_amount'))->when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->where('status_id', PaymentStatus::COMPLETED->value)
            ->groupBy('created_by')
            ->get();
            // dd($payments_list);
        if($request->ajax()) {
            // Validation
            if(($request->filter_name == "show") AND empty($request->date_from) AND empty($request->date_to)) {
                $request->validate([
                    'date_from' => 'required|date_format:d-m-Y',
                    'date_to' => 'required|date_format:d-m-Y',
                ]);
            }
            // $request->validate(['status' => 'required']);
            return view('reports.employee.collection-report.list-body');
        }
        return view('reports.employee.collection-report.list');
    }
}