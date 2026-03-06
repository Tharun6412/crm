<?php
namespace App\Http\Controllers\Reports;

use App\Enums\PaymentStatuuseuse App\Enums\SDPaymentStatus;
use App\Http\Controllers\Controller;
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
        // Fetching Invoice Payments
        $invoicePayments = InvoicePayment::select(
            'pay_invoice_payments.created_by',
            'users.emp_id',
            DB::raw('CONCAT_WS(" ",users.first_name,users.last_name) as emp_name'),
            'mst_gas.name as ga_name',
            DB::raw('SUM(pay_invoice_payments.amount) as invoice_amount'),
            DB::raw('0 as sd_amount')
        )
        ->leftJoin('users', 'users.id', '=', 'pay_invoice_payments.created_by')
        ->join('bil_invoices', 'bil_invoices.id', '=', 'pay_invoice_payments.invoice_id')
        ->join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
        ->join('mst_gas', 'mst_gas.id', '=', 'cns_consumers.ga_id')
        ->where('pay_invoice_payments.status_id', PaymentStatus::COMPLETED->value)
        ->when(!isAdmin() AND !isSuperAdmin(), function ($q) {
            $q->whereIn('cns_consumers.ga_id', session('user')['gas']);
        })
        ->when(request('ga_id'), function ($q) {
            $q->where('cns_consumers.ga_id', request('ga_id'));
        })
        ->when(($request->filter_name == "show") AND $request->filled('date_from') AND $request->filled('date_to'), function ($q) use ($request) {
            $q->whereBetween('pay_invoice_payments.created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
        })
        ->groupBy('pay_invoice_payments.created_by','users.emp_id','users.first_name','users.last_name','mst_gas.name');
        // Fetching SD Amounts
        $sdPayments = ConsumerSdPayment::select(
            'cns_consumer_sd_payments.created_by',
            'users.emp_id',
            DB::raw('CONCAT_WS(" ",users.first_name,users.last_name) as emp_name'),
            'mst_gas.name as ga_name',
            DB::raw('0 as invoice_amount'),
            DB::raw('SUM(cns_consumer_sd_payments.amount) as sd_amount')
        )
        ->leftJoin('users', 'users.id', '=', 'cns_consumer_sd_payments.created_by')
        ->join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_sd_payments.consumer_id')
        ->join('mst_gas', 'mst_gas.id', '=', 'cns_consumers.ga_id')
        ->where('cns_consumer_sd_payments.status_id', SDPaymentStatus::PAID->value)
        ->when(!isAdmin() AND !isSuperAdmin(), function ($q) {
            $q->whereIn('cns_consumers.ga_id', session('user')['gas']);
        })
        ->when(request('ga_id'), function ($q) {
            $q->where('cns_consumers.ga_id', request('ga_id'));
        })
        ->when(($request->filter_name == "show") AND $request->filled('date_from') AND $request->filled('date_to'), function ($q) use ($request) {
            $q->whereBetween('cns_consumer_sd_payments.created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()]);
        })
        ->groupBy('cns_consumer_sd_payments.created_by','users.emp_id','users.first_name','users.last_name','mst_gas.name');
        $result = DB::query()
            ->fromSub($invoicePayments->unionAll($sdPayments), 'payments')
            ->select(
                'created_by',
                'emp_id',
                'emp_name',
                'ga_name',
                DB::raw('SUM(invoice_amount) as invoice_amount'),
                DB::raw('SUM(sd_amount) as sd_amount'),
                DB::raw('SUM(invoice_amount+sd_amount) as total_amount')
            )
            ->groupBy('created_by','emp_id','emp_name','ga_name')
            ->orderByDesc('invoice_amount')
            ->paginate(50)->withQueryString();
        // dd($result);
        if($request->ajax()) {
            // Validation
            if(($request->filter_name == "show") AND empty($request->date_from) AND empty($request->date_to)) {
                $request->validate([
                    'date_from' => 'required|date_format:d-m-Y',
                    'date_to' => 'required|date_format:d-m-Y',
                ]);
            }
            $request->validate(['ga_id' => 'required']);
            return view('reports.employee.collection-report.list-body', ['result' => $result, 'geo_areas' => $geo_areas]);
        }
        return view('reports.employee.collection-report.list', ['geo_areas' => $geo_areas]);
    }
}