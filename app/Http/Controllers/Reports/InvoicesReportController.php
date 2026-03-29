<?php
namespace App\Http\Controllers\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Exports\Reports\InvoicesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * All invoices report controller
 * 
 */
class InvoicesReportController extends Controller
{
    public function index(Request $request)
    {
        /*$sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'bil_invoices.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 20;
        $today = Carbon::today();
        $invoices = BillInvoice::join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id', '=', 'bil_invoices.id')
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
                $q->whereIn('cns_consumers.segment_id', $request->segments);
            })
             ->when($request->filled('status_id'), function ($q) use($request) {
                $q->whereIn('bil_invoices.status_id', $request->status_id);
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->whereIn('cns_consumers.connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('cns_consumers.ga_id', $request->geo_area);
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereIn('cns_consumers.district_id', $request->district);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('bil_invoices.invoice_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->select('bil_invoices.id','bil_invoices.invoice_number','bil_invoices.invoice_date','bil_invoices.type_id','bil_invoices.due_date','bil_invoices.total_amount', 'bil_invoices.payable_amount', 'bil_invoices.balance_amount', 'bil_invoices.consumer_id', 'bil_invoice_consumption.net_consumption', 'bil_invoices.status_id')
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        // Render output
        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.list-body', ['invoices' => $invoices]);
        }
        return view('reports.invoice.invoice-report.list', ['invoices' => $invoices]);*/

        $sortBy  = $request->get('sortBy', 'bil_invoices.created_at');
        $sortOr  = $request->get('sortOr', 'desc');
        $records = (int) $request->get('records', 20);
        $today   = Carbon::today()->toDateString();

        // Your original query — kept exactly as you wrote it
        $query = BillInvoice::join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->leftJoin('bil_invoice_consumption', 'bil_invoice_consumption.invoice_id', '=', 'bil_invoices.id')
            ->where('bil_invoices.status_id', '!=', InvoiceStatus::CANCEL->value)
            ->when($request->has('range'), function ($q) use ($request, $today) {
                match ($request->range) {
                    '0'     => $q->where('bil_invoices.due_date', '>=', $today),
                    '1-15'  => $q->whereBetween('bil_invoices.due_date', [now()->subDays(15)->toDateString(),  now()->subDay()->toDateString()]),
                    '16-30' => $q->whereBetween('bil_invoices.due_date', [now()->subDays(30)->toDateString(),  now()->subDays(16)->toDateString()]),
                    '31-60' => $q->whereBetween('bil_invoices.due_date', [now()->subDays(60)->toDateString(),  now()->subDays(31)->toDateString()]),
                    '61-90' => $q->whereBetween('bil_invoices.due_date', [now()->subDays(90)->toDateString(),  now()->subDays(61)->toDateString()]),
                    '90+'   => $q->where('bil_invoices.due_date', '<',   now()->subDays(90)->toDateString()),
                    default => null,
                };
            })
            ->when($request->filled('ga_id'), fn ($q) => $q->where('cns_consumers.ga_id', $request->ga_id))
            ->when($request->filled('invoice_type'), fn ($q) => $q->whereIn('bil_invoices.type_id', (array) $request->invoice_type))
            ->when($request->filled('key'), fn ($q) => $q->where(fn ($q) =>
                $q->where('bil_invoices.invoice_number', 'like', '%' . $request->key . '%')
                ->orWhere('cns_consumers.crn', 'like', '%' . $request->key . '%')
            ))
            ->when($request->has('segments'), fn ($q) => $q->whereIn('cns_consumers.segment_id', (array) $request->segments))
            ->when($request->filled('status_id'), fn ($q) => $q->whereIn('bil_invoices.status_id', (array) $request->status_id))
            ->when($request->filled('connection_type_id'), fn ($q) => $q->whereIn('cns_consumers.connection_type_id', (array) $request->connection_type_id))
            ->when($request->has('geo_area'), fn ($q) => $q->whereIn('cns_consumers.ga_id', (array) $request->geo_area))
            ->when($request->has('district'), fn ($q) => $q->whereIn('cns_consumers.district_id', (array) $request->district))
            ->when(!empty($request->date_from) && !empty($request->date_to), fn ($q) =>
                $q->whereBetween('bil_invoices.invoice_date', [
                    Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(),
                    Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay(),
                ])
            )
            ->select(
                'bil_invoices.id', 'bil_invoices.invoice_number', 'bil_invoices.invoice_date',
                'bil_invoices.type_id', 'bil_invoices.due_date', 'bil_invoices.total_amount',
                'bil_invoices.payable_amount', 'bil_invoices.balance_amount',
                'bil_invoices.consumer_id', 'bil_invoices.status_id',
                'bil_invoice_consumption.net_consumption'
            )
            ->orderBy($sortBy, $sortOr);

        // ── Only change: bypass paginate()'s COUNT using a cloned count query ──────
        $countSql   = $query->clone()->reorder()->toSql();
        $bindings   = $query->clone()->reorder()->getBindings();
        $total      = DB::select("SELECT COUNT(*) as aggregate FROM ({$countSql}) as sub", $bindings)[0]->aggregate;

        $page     = (int) $request->get('page', 1);
        $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
            $query->offset(($page - 1) * $records)->limit($records)->get(),
            $total,
            $records,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.list-body', compact('invoices'));
        }

        return view('reports.invoice.invoice-report.list', compact('invoices'));
    }

    /**
     * Export the invoices based on GA and range of due days.
     */
    public function invoicesReportExport(Request $request)
    {
        return (new InvoicesReportExport($request))->download('InvoicesReport.xlsx');
    }

    /**
     * Inovices List
     */
    public function list(Request $request)
    {
        $sortBy  = $request->get('sortBy', 'created_at');
        $sortOr  = $request->get('sortOr', 'desc');
        $records = (int) $request->get('records', 0);
        // $today   = Carbon::today()->toDateString();

        // Query
        $invoices = BillInvoice::with([
                'consumer:id,crn,fname,lname,ga_id',
                'consumer.ga:id,name',
                'invoiceType:id,name',
                'status:id,name'
            ])
            ->when($request->filled('key'), fn ($q) => $q->where(fn ($q) =>
                $q->where('invoice_number', 'like', '%' . $request->key . '%')
                    ->orWhereHas('consumer', fn ($q) => $q->where('crn', 'like', '%' . $request->key . '%'))
            ))
            ->when(!empty($request->date_from) and !empty($request->date_to), fn ($q) =>
                $q->whereBetween('invoice_date', [
                    Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(),
                    Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay(),
                ])
            )
            ->when($request->filled('invoice_type'), fn ($q) => $q->whereIn('type_id', (array) $request->invoice_type))
            ->whereNot('status_id', InvoiceStatus::CANCEL->value)
            ->when($request->filled('status_id'), fn ($q) => $q->whereIn('status_id', (array) $request->status_id))
            ->select([ 'id', 'consumer_id' ,'invoice_number', 'invoice_date', 'due_date', 'payable_amount', 'balance_amount', 'status_id', 'type_id'])
            // ->when($sortBy !== 'id', fn($q) => $q->orderBy($sortBy, $sortOr))
            ->orderBy('id', $sortOr) // Cursor pagination requires a unique column as tiebreaker
            ->cursorPaginate($records)->withQueryString();
        // dd($invoices);
        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.invoices-list-body', compact('invoices'));
        }

        return view('reports.invoice.invoice-report.invoices-list', compact('invoices'));
    }
}