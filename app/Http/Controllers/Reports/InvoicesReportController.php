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