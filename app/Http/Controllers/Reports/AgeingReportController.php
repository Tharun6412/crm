<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Exports\Reports\AgingInvoicesExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\BillInvoiceType;
use App\Models\Master\Ga;
use Carbon\Carbon;
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
                    ->where('bil_invoices.status_id', InvoiceStatus::NOT_PAID->value)
                    ->whereNotIn('bil_invoices.type_id', [
                        InvoiceType::LATE_PAYMENT_CHARGES->value,
                        InvoiceType::RENTAL_CHARGES->value,
                        InvoiceType::SD_EMI->value
                    ]);
                if ($request->filled('invoice_type')) {
                    $join->where('bil_invoices.type_id', $request->invoice_type);
                }
            })
            ->selectRaw("mst_gas.id as ga_id,
                mst_gas.name as ga_name,
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

    /**
     * Invoice Details based on the GA and the range of crossed due date.
     */
    public function invoicesList(Request $request)
    {
        $today = Carbon::today();
        $query = BillInvoice::query()
            ->join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->where('cns_consumers.ga_id', $request->ga_id)
            ->whereNotIn('bil_invoices.type_id', [
                    InvoiceType::LATE_PAYMENT_CHARGES->value,
                    InvoiceType::RENTAL_CHARGES->value,
                    InvoiceType::SD_EMI->value
                ])
            ->where('bil_invoices.status_id', InvoiceStatus::NOT_PAID->value);
        if ($request->filled('invoice_type')) {
            $query->where('bil_invoices.type_id', $request->invoice_type);
        }
        // Aging Filter
        switch ($request->range) 
        {
            case '1-15':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(15),
                    $today->copy()->subDay()
                ]);
                break;
            case '16-30':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(30),
                    $today->copy()->subDays(16)
                ]);
                break;
            case '31-60':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(60),
                    $today->copy()->subDays(31)
                ]);
                break;
            case '61-90':
                $query->whereBetween('bil_invoices.due_date', [
                    $today->copy()->subDays(90),
                    $today->copy()->subDays(61)
                ]);
                break;
            case '90+':
                $query->where('bil_invoices.due_date', '<', $today->copy()->subDays(90));
                break;
        }
        // Search Filters
        $query->when(($request->filled('key')), function($q) use($request) {
            $q->where('bil_invoices.invoice_number', 'like', '%' . $request->key . '%');
        });
        $invoices = $query->select('invoice_number','invoice_date','type_id','due_date','total_amount', 'payable_amount', 'balance_amount', 'consumer_id')
            ->orderBy('invoice_date', 'desc')
            ->paginate(20)->withQueryString();
        // Render output
        if ($request->ajax() && ($request->has('page') || $request->has('is_filter'))) {
            return view('reports.consumer.aging-report.invoices-list-body', ['invoices' => $invoices]);
        }
        return view('reports.consumer.aging-report.invoices-list', ['invoices' => $invoices]);
    }

    /**
     * Export the invoices based on GA and range of due days.
     */
    public function agingInvoicesExport(Request $request)
    {
        return (new AgingInvoicesExport($request))->download('AgingReport.xlsx');
    }
}