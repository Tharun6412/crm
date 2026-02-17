<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * All invoices report controller
 * 
 */
class InvoicesReportController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $query = BillInvoice::query()
        ->join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
        ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id', '=', 'bil_invoices.id')
        ->whereNotIn('bil_invoices.type_id', [
            InvoiceType::LATE_PAYMENT_CHARGES->value,
            InvoiceType::RENTAL_CHARGES->value,
            InvoiceType::SD_EMI->value,
            ])
        ->where('bil_invoices.status_id', InvoiceStatus::NOT_PAID->value)
        ->whereNot('bil_invoices.status_id', InvoiceStatus::CANCEL->value);
        if ($request->filled('ga_id')) {
            $query->where('cns_consumers.ga_id', $request->ga_id);
        }
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
            $q->where('cns_consumers.crn', 'like', '%' . $request->key . '%');
        });
        $invoices = $query->select('invoice_number','invoice_date','type_id','due_date','total_amount', 'payable_amount', 'balance_amount', 'consumer_id', 'bil_invoice_consumption.net_consumption', 'bil_invoices.status_id')
            ->orderBy('invoice_date', 'desc')
            ->paginate(20)->withQueryString();
        // Render output
        
        if ($request->ajax()) {
            dd($request);

            return view('reports.invoice.invoice-report.list-body', ['invoices' => $invoices]);
        }
        return view('reports.invoice.invoice-report.list', ['invoices' => $invoices]);
    }
}