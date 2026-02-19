<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\BillStatus;
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
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'bil_invoices.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 20;
        $today = Carbon::today();
        $invoice_status = BillStatus::all();
        $invoices = BillInvoice::join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id', '=', 'bil_invoices.id')
            ->whereNotIn('bil_invoices.type_id', [
                InvoiceType::LATE_PAYMENT_CHARGES->value,
                InvoiceType::RENTAL_CHARGES->value,
                InvoiceType::SD_EMI->value,
                ])
            ->whereNot('bil_invoices.status_id', InvoiceStatus::CANCEL->value)
            ->when($request->has('range'), function ($q) use($request, $today) {
                // Aging Filter
                switch ($request->range) 
                {
                    case '0':
                        $q->where('bil_invoices.due_date', '>=', $today);
                        break;
                    case '1-15':
                        $q->whereBetween('bil_invoices.due_date', [
                            $today->copy()->subDays(15),
                            $today->copy()->subDay()
                        ]);
                        break;
                    case '16-30':
                        $q->whereBetween('bil_invoices.due_date', [
                            $today->copy()->subDays(30),
                            $today->copy()->subDays(16)
                        ]);
                        break;
                    case '31-60':
                        $q->whereBetween('bil_invoices.due_date', [
                            $today->copy()->subDays(60),
                            $today->copy()->subDays(31)
                        ]);
                        break;
                    case '61-90':
                        $q->whereBetween('bil_invoices.due_date', [
                            $today->copy()->subDays(90),
                            $today->copy()->subDays(61)
                        ]);
                        break;
                    case '90+':
                        $q->where('bil_invoices.due_date', '<', $today->copy()->subDays(90));
                        break;
                    default;
                }
                $q->whereIn('bil_invoices.status_id', [InvoiceStatus::NOT_PAID->value, InvoiceStatus::PARTIALLY_PAID->value]);
            })
            ->when($request->filled('ga_id'), function ($q) use($request) {
                $q->where('cns_consumers.ga_id', $request->ga_id);
            })
            ->when($request->filled('invoice_type'), function ($q) use($request) {
                $q->whereIn('bil_invoices.type_id', $request->invoice_type);
            })
            ->when(($request->filled('key')), function($q) use($request) {
                $q->where(function($q) use($request){
                    $q->where('bil_invoices.invoice_number', 'like', '%' . $request->key . '%');
                    $q->orWhere('cns_consumers.crn', 'like', '%' . $request->key . '%');
                });
            })
            ->when($request->has('segments'), function ($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
             ->when($request->filled('status_id'), function ($q) use($request) {
                $q->where('bil_invoices.status_id', $request->status_id);
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->where('connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereIn('district_id', $request->district);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('bil_invoices.invoice_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->select('bil_invoices.id','invoice_number','invoice_date','type_id','due_date','total_amount', 'payable_amount', 'balance_amount', 'consumer_id', 'bil_invoice_consumption.net_consumption', 'bil_invoices.status_id')
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        // Render output
        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.list-body', ['invoices' => $invoices]);
        }
        return view('reports.invoice.invoice-report.list', ['invoices' => $invoices]);
    }
}