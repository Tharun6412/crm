<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

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
        $gas = Ga::select('id', 'name')->get();
        $invoices = DB::table('bil_invoices')
        ->join('cns_consumers', 'bil_invoices.consumer_id', '=', 'cns_consumers.id')
        ->select(
                'cns_consumers.ga_id',
                DB::raw("
                    SUM(CASE WHEN due_date >= CURDATE()                                      THEN balance_amount ELSE 0 END) AS no_due_days,
                    SUM(CASE WHEN due_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 15 DAY)  AND DATE_SUB(CURDATE(), INTERVAL  1 DAY)  THEN balance_amount ELSE 0 END) AS range_1_15,
                    SUM(CASE WHEN due_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY)  AND DATE_SUB(CURDATE(), INTERVAL 16 DAY)  THEN balance_amount ELSE 0 END) AS range_16_30,
                    SUM(CASE WHEN due_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY)  AND DATE_SUB(CURDATE(), INTERVAL 31 DAY)  THEN balance_amount ELSE 0 END) AS range_31_60,
                    SUM(CASE WHEN due_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 90 DAY)  AND DATE_SUB(CURDATE(), INTERVAL 61 DAY)  THEN balance_amount ELSE 0 END) AS range_61_90,
                    SUM(CASE WHEN due_date <  DATE_SUB(CURDATE(), INTERVAL 90 DAY)                                                THEN balance_amount ELSE 0 END) AS range_gt90
                "),
            )
        ->whereIn('bil_invoices.status_id', [InvoiceStatus::NOT_PAID->value, InvoiceStatus::PARTIALLY_PAID->value])
        ->when($request->filled('invoice_type'), fn($q) =>
            $q->whereIn('bil_invoices.type_id', $request->invoice_type)
        )
        ->groupBy('cns_consumers.ga_id')
        ->orderBy('cns_consumers.ga_id')->get()->keyBy('ga_id');
        // Render output
        if($request->ajax()) {
            return view('reports.consumer.aging-report.list-body', ['gas' => $gas,'invoices' => $invoices,]);
        }
        return view('reports.consumer.aging-report.list', ['gas' => $gas,'invoices' => $invoices,]);
    }
    
}