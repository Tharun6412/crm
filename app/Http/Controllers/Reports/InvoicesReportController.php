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
    /**
     * Invoice Report
     */
    public function index(Request $request)
    {
        $sortBy  = $request->get('sortBy', 'created_at');
        $sortOr  = $request->get('sortOr', 'desc');
        $records = (int) $request->get('records', 20);
        // $today   = Carbon::today()->toDateString();

        // Query
        $query = BillInvoice::query()
            ->when($request->filled('key'), fn ($q) => $q->where(fn ($q) =>
                $q->where('invoice_number', $request->key)
                    ->orWhereHas('consumer', fn ($q) => $q->where('crn', $request->key))
            ))
            ->when(!empty($request->date_from) and !empty($request->date_to), fn ($q) =>
                $q->whereBetween('invoice_date', [
                    Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay(),
                    Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay(),
                ])
            )
            ->when($request->filled('invoice_type'), fn ($q) => $q->whereIn('type_id', (array) $request->invoice_type))
            ->when(
                $request->filled('segments') ||
                $request->filled('connection_type_id') ||
                $request->filled('geo_area'),
                function ($q) use ($request) {
                    $q->whereHas('consumer', function ($q) use ($request) {
                        if ($request->filled('segments')) {
                            $q->whereIn('segment_id', $request->segments);
                        }
                        if ($request->filled('connection_type_id')) {
                            $q->whereIn('connection_type_id', $request->connection_type_id);
                        }
                        if ($request->filled('geo_area')) {
                            $q->whereIn('ga_id', $request->geo_area);
                        }
                    });
                }
            )
            ->whereNot('status_id', InvoiceStatus::CANCEL->value)
            ->when($request->filled('status_id'), fn ($q) => $q->whereIn('status_id', (array) $request->status_id));

        $tRecords = (clone $query)->count();
        $invoices = (clone $query)
            ->with([
                'consumer:id,crn,fname,lname,ga_id,segment_id,district_id,connection_type_id',
                'consumer.ga:id,name',
                'consumer.segment:id,name',
                'consumer.district:id,name',
                'consumer.connectType:id,name',
                'invoiceType:id,name',
                'status:id,name',
                'consumption:invoice_id,net_consumption',
            ])->select([ 'id', 'consumer_id' ,'invoice_number', 'invoice_date', 'due_date', 'taxable_amount', 'tax_amount', 'total_amount', 'payable_amount', 'balance_amount', 'status_id', 'type_id'])
            ->when($sortBy !== 'id', fn($q) => $q->orderBy($sortBy, $sortOr))
            ->orderBy('id', $sortOr) // Cursor pagination requires a unique column as tiebreaker
            ->cursorPaginate($records)->withQueryString();
        $pageTotals = [
            'net_consumption' => $invoices->getCollection()->sum(fn($inv) => $inv->consumption->net_consumption ?? 0),
            'payable_amount' => $invoices->getCollection()->sum('payable_amount'),
            'tax_amount'     => $invoices->getCollection()->sum('tax_amount'),
            'taxable_amount' => $invoices->getCollection()->sum('taxable_amount'),
            'total_amount'   => $invoices->getCollection()->sum('total_amount'),
            'balance_amount' => $invoices->getCollection()->sum('balance_amount'),
        ];
       $totals = (clone $query)
            ->selectRaw('
                SUM(taxable_amount) as total_taxable,
                SUM(tax_amount) as total_tax,
                SUM(total_amount) as total_amount,
                SUM(payable_amount) as total_payable,
                SUM(balance_amount) as total_balance
            ')
            ->first();
        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.list-body', compact('invoices','tRecords','pageTotals','totals'));
        }

        return view('reports.invoice.invoice-report.list', compact('invoices', 'tRecords', 'pageTotals', 'totals'));
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
        $records = (int) $request->get('records', 20);
        // $today   = Carbon::today()->toDateString();

        // Query
        $query = BillInvoice::with([
                'consumer:id,crn,fname,lname,ga_id',
                'consumer.ga:id,name',
                'invoiceType:id,name',
                'status:id,name'
            ])
            ->when($request->filled('key'), fn ($q) => $q->where(fn ($q) =>
                $q->where('invoice_number', $request->key)
                    ->orWhereHas('consumer', fn ($q) => $q->where('crn', $request->key))
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
            ->when($request->filled('geo_area'), fn($q) =>
                $q->whereHas('consumer', fn($q) =>
                    $q->whereIn('ga_id', $request->geo_area)
                )
            )
            ->when($request->has('range'), function ($query) use ($request) {
                match ($request->range) {
                    '0'    => $query->where('due_date', '>=', now()->toDateString()),
                    '1-15' => $query->whereBetween('due_date', [
                                    now()->subDays(15)->toDateString(),
                                    now()->subDay()->toDateString(),
                                ]),
                    '16-30' => $query->whereBetween('due_date', [
                                    now()->subDays(30)->toDateString(),
                                    now()->subDays(16)->toDateString(),
                                ]),
                    '31-60' => $query->whereBetween('due_date', [
                                    now()->subDays(60)->toDateString(),
                                    now()->subDays(31)->toDateString(),
                                ]),
                    '61-90' => $query->whereBetween('due_date', [
                                    now()->subDays(90)->toDateString(),
                                    now()->subDays(61)->toDateString(),
                                ]),
                    '90+'  => $query->where('due_date', '<', now()->subDays(90)->toDateString()),
                    default => $query, // no range filter — return all
                }; // your scope
            });

        $tRecords = (clone $query)->count();
        $invoices = (clone $query)->select([ 'id', 'consumer_id' ,'invoice_number', 'invoice_date', 'due_date', 'payable_amount', 'balance_amount', 'status_id', 'type_id'])
            // ->when($sortBy !== 'id', fn($q) => $q->orderBy($sortBy, $sortOr))
            ->orderBy('id', $sortOr) // Cursor pagination requires a unique column as tiebreaker
            ->cursorPaginate($records)->withQueryString();
        // dd($invoices);
        if ($request->ajax()) {
            return view('reports.invoice.invoice-report.invoices-list-body', compact('invoices','tRecords'));
        }

        return view('reports.invoice.invoice-report.invoices-list', compact('invoices', 'tRecords'));
    }
}