<?php

namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Gas Sale Report GA Wise
 */
class GasSaleReportController extends Controller
{
    /**
     * Gas sale list
     */
    public function index(Request $request)
    {
        $fromDate = $request->filled('date_from')
            ? Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()
            : Carbon::now()->subMonthNoOverflow()->startOfMonth()->startOfDay();

        $toDate = $request->filled('date_to')
            ? Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()
            : Carbon::now()->subMonthNoOverflow()->endOfMonth()->endOfDay();

        $geo_areas = Ga::select('id', 'name')->get();
        // Get Gas Sale Consumption Details 
        $gas_sale = DB::table(DB::raw('bil_invoices FORCE INDEX (type_date_index)'))
            ->join(DB::raw('bil_invoice_consumption as bic FORCE INDEX (inv_consumption_index)'),'bic.invoice_id', '=', 'bil_invoices.id')
            ->join(DB::raw('cns_consumers as c FORCE INDEX (filter_index)'), 'c.id', '=', 'bil_invoices.consumer_id')
            ->where('bil_invoices.type_id', InvoiceType::GAS_BILL->value)
            ->whereBetween('bil_invoices.invoice_date', [$fromDate, $toDate])
            ->groupBy('c.ga_id', 'c.segment_id', 'c.connection_type_id')
            ->selectRaw('
                c.ga_id,
                c.segment_id,
                c.connection_type_id,
                SUM(bic.net_consumption) as total_sale
            ')
            ->get();
        // Array Preparation
        $gas_sale_array = [];
        foreach ($gas_sale as $row) {
            $gas_sale_array[$row->ga_id][$row->segment_id][$row->connection_type_id] = $row->total_sale;
        }
        // Render output
        if($request->ajax()) {
            return view('reports.dashboard.gas-sale-report.list-body', ['geo_areas' => $geo_areas, 'gas_sale_array' => $gas_sale_array, 'date_from' => $fromDate,'date_to' => $toDate]);
        }
        return view('reports.dashboard.gas-sale-report.list', ['geo_areas' => $geo_areas, 'gas_sale_array' => $gas_sale_array, 'date_from' => $fromDate, 'date_to' => $toDate]);
    }
}