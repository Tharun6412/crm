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
        $gas_sale = DB::table('bil_invoice_consumption as bic')
            ->join('bil_invoices as bi', function ($join) use ($fromDate, $toDate) {
                $join->on('bi.id', '=', 'bic.invoice_id')
                    ->where('bi.type_id', InvoiceType::GAS_BILL->value)
                    ->whereBetween('bi.invoice_date', [$fromDate, $toDate]);
            })
            ->join('cns_consumers as c', 'c.id', '=', 'bi.consumer_id')
            ->whereIn('c.segment_id', [1,2]) // reduce scan
            ->whereIn('c.connection_type_id', [1,2]) // reduce scan
            ->groupBy('c.ga_id')
            ->selectRaw('
                c.ga_id,
                SUM(IF(c.segment_id=1 AND c.connection_type_id=2, bic.net_consumption, 0)) as dom_pre,
                SUM(IF(c.segment_id=1 AND c.connection_type_id=1, bic.net_consumption, 0)) as dom_post,
                SUM(IF(c.segment_id=2 AND c.connection_type_id=2, bic.net_consumption, 0)) as com_pre,
                SUM(IF(c.segment_id=2 AND c.connection_type_id=1, bic.net_consumption, 0)) as com_post
            ')
            ->get()
            ->keyBy('ga_id');
        // Render output
        if($request->ajax()) {
            return view('reports.dashboard.gas-sale-report.list-body', ['geo_areas' => $geo_areas, 'gas_sale' => $gas_sale, 'date_from' => $fromDate,'date_to' => $toDate]);
        }
        return view('reports.dashboard.gas-sale-report.list', ['geo_areas' => $geo_areas, 'gas_sale' => $gas_sale, 'date_from' => $fromDate, 'date_to' => $toDate]);

    }
    // public function index(Request $request)
    // {
    //     $fromDate = $request->filled('date_from')
    //         ? Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()
    //         : Carbon::now()->subMonthNoOverflow()->startOfMonth()->startOfDay();

    //     $toDate = $request->filled('date_to')
    //         ? Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()
    //         : Carbon::now()->subMonthNoOverflow()->endOfMonth()->endOfDay();

    //     $gaGasSales = Ga::leftJoin('cns_consumers','cns_consumers.ga_id','=','mst_gas.id')
    //         ->leftJoin('bil_invoices', function ($join) use ($fromDate, $toDate) {
    //             $join->on('bil_invoices.consumer_id', '=', 'cns_consumers.id')
    //             ->where('bil_invoices.type_id', InvoiceType::GAS_BILL->value);
    //             $join->whereBetween('bil_invoices.invoice_date', [$fromDate,$toDate]);
    //         })
    //         ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id','=','bil_invoices.id')
    //         ->selectRaw('
    //             mst_gas.id as ga_id,
    //             mst_gas.name as ga_name,
    //             SUM(CASE 
    //                     WHEN cns_consumers.segment_id = 1 
    //                     AND cns_consumers.connection_type_id = 2 
    //                 THEN bil_invoice_consumption.net_consumption 
    //                 ELSE 0 
    //             END) as dom_pre,
    //             SUM(CASE 
    //                     WHEN cns_consumers.segment_id = 1 
    //                     AND cns_consumers.connection_type_id = 1 
    //                 THEN bil_invoice_consumption.net_consumption 
    //                 ELSE 0 
    //             END) as dom_post,
    //             SUM(CASE 
    //                     WHEN cns_consumers.segment_id = 2 
    //                     AND cns_consumers.connection_type_id = 2 
    //                 THEN bil_invoice_consumption.net_consumption 
    //                 ELSE 0 
    //             END) as com_pre,
    //             SUM(CASE 
    //                     WHEN cns_consumers.segment_id = 2 
    //                     AND cns_consumers.connection_type_id = 1 
    //                 THEN bil_invoice_consumption.net_consumption 
    //                 ELSE 0 
    //             END) as com_post
    //         ')
    //         ->groupBy('mst_gas.id', 'mst_gas.name')
    //         ->orderBy('mst_gas.id', 'asc')
    //         ->get();
    //     // Render output
    //     if($request->ajax()) {
    //         return view('reports.dashboard.gas-sale-report.list-body', ['gaGasSales' => $gaGasSales,'date_from' => $fromDate,'date_to' => $toDate]);
    //     }
    //     return view('reports.dashboard.gas-sale-report.list', ['gaGasSales' => $gaGasSales, 'date_from' => $fromDate, 'date_to' => $toDate]);

    // }
}