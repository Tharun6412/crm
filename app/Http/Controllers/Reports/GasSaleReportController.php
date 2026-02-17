<?php

namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use Illuminate\Http\Request;

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
        $gaGasSales = Ga::leftJoin('cns_consumers','cns_consumers.ga_id','=','mst_gas.id')
            ->leftJoin('bil_invoices', function ($join) use ($request) {
                $join->on('bil_invoices.consumer_id', '=', 'cns_consumers.id')
                ->where('bil_invoices.type_id', InvoiceType::GAS_BILL->value);
                if ($request->filled('from_date') && $request->filled('to_date')) {
                    $join->whereBetween('bil_invoices.invoice_date', [
                        $request->from_date,
                        $request->to_date
                    ]);
                }
            })
            ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id','=','bil_invoices.id')
            ->selectRaw('
                mst_gas.id as ga_id,
                mst_gas.name as ga_name,
                SUM(CASE 
                        WHEN cns_consumers.segment_id = 1 
                        AND cns_consumers.connection_type_id = 2 
                    THEN bil_invoice_consumption.net_consumption 
                    ELSE 0 
                END) as dom_pre,
                SUM(CASE 
                        WHEN cns_consumers.segment_id = 1 
                        AND cns_consumers.connection_type_id = 1 
                    THEN bil_invoice_consumption.net_consumption 
                    ELSE 0 
                END) as dom_post,
                SUM(CASE 
                        WHEN cns_consumers.segment_id = 2 
                        AND cns_consumers.connection_type_id = 2 
                    THEN bil_invoice_consumption.net_consumption 
                    ELSE 0 
                END) as com_pre,
                SUM(CASE 
                        WHEN cns_consumers.segment_id = 2 
                        AND cns_consumers.connection_type_id = 1 
                    THEN bil_invoice_consumption.net_consumption 
                    ELSE 0 
                END) as com_post
            ')
            ->groupBy('mst_gas.id', 'mst_gas.name')
            ->orderBy('mst_gas.id', 'asc')
            ->get();
            // dd($gaGasSales);
        // Render output
        if($request->ajax()) {
            return view('reports.dashboard.gas-sale-report.list-body', ['gaGasSales' => $gaGasSales]);
        }
        return view('reports.dashboard.gas-sale-report.list', ['gaGasSales' => $gaGasSales]);

    }
}