<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\Ga;
use Illuminate\Http\Request;

/**
 * Ageing Report Controller (Invoices Ageing Report).
 */
class AgeingReportController extends Controller
{
    /**
     * GA wise counts of the due date crossed invoices.
     */
    public function index(Request $request)
    {  
        $invoice_types = BillInvoiceType::all();
        $gasAging = Ga::leftJoin('cns_consumers', 'mst_gas.id', '=', 'cns_consumers.ga_id')
            ->leftJoin('bil_invoices', function ($join) use ($request) {
                $join->on('bil_invoices.consumer_id', '=', 'cns_consumers.id')
                    ->whereIn('bil_invoices.status_id', [InvoiceStatus::NOT_PAID->value,InvoiceStatus::PARTIALLY_PAID->value])
                    ->whereNot('bil_invoices.status_id', InvoiceStatus::CANCEL->value);
                if ($request->filled('invoice_type')) {
                    $join->whereIn('bil_invoices.type_id', $request->invoice_type);
                }
            })
            ->selectRaw("mst_gas.id as ga_id,
                mst_gas.name as ga_name,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) < 1 THEN bil_invoices.balance_amount ELSE 0 END) as no_due_days,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) BETWEEN 1 AND 15 THEN bil_invoices.balance_amount ELSE 0 END) as range_1_15,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) BETWEEN 16 AND 30 THEN bil_invoices.balance_amount ELSE 0 END) as range_16_30,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) BETWEEN 31 AND 60 THEN bil_invoices.balance_amount ELSE 0 END) as range_31_60,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) BETWEEN 61 AND 90 THEN bil_invoices.balance_amount ELSE 0 END) as range_61_90,
                SUM(CASE WHEN DATEDIFF(NOW(), due_date) > 90 THEN bil_invoices.balance_amount ELSE 0 END) as range_gt90
            ")
            ->groupBy('mst_gas.id', 'mst_gas.name')
            ->orderBy('mst_gas.id', 'asc')
            ->get();
        // Render output
        if($request->ajax()) {
            return view('reports.consumer.aging-report.list-body', ['gasAging' => $gasAging, 'invoice_types' => $invoice_types]);
        }
        return view('reports.consumer.aging-report.list', ['gasAging' => $gasAging, 'invoice_types' => $invoice_types]);
    }
    
}