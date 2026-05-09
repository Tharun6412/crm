<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConnectionType;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceType;
use App\Exports\Consumers\ConsumerExport;
use App\Exports\Reports\UnbilledConsumersExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnbilledReport extends Controller
{
    public function index(Request $request)
    {

        $gas = Ga::select('id', 'name')->get();
        $consumers = DB::table('cns_consumers')
         ->leftJoin('bil_invoices', function ($join) {
             $join->on('bil_invoices.consumer_id', '=', 'cns_consumers.id')
                  ->where('bil_invoices.invoice_date', '>=', now()->subDays(60))
                  ->where('bil_invoices.type_id', InvoiceType::GAS_BILL->value);
         })
        ->select([
            'cns_consumers.ga_id',
            DB::raw('COUNT(cns_consumers.id) as unbilled_count')
        ])
        ->where('cns_consumers.status_id', ConsumerStatus::ACTIVATE->value)
        ->where('cns_consumers.connection_type_id', ConnectionType::POSTPAID->value)
        ->whereNull('bil_invoices.id')           // No invoice in last 60 days
        ->when($request->filled('invoice_type'), fn($q) => $q->whereIn('bil_invoices.type_id', $request->invoice_type))
        ->when($request->filled('segments'), fn($q) => $q->whereIn('cns_consumers.segment_id', $request->segments))
        ->groupBy('cns_consumers.ga_id')
        ->orderBy('cns_consumers.ga_id')->get()->keyBy('ga_id');
        $totalUnbilled = $consumers->sum('unbilled_count');
        // dd($consumers);
        if($request->ajax()) {
            return view('reports.consumer.unbilled-report.list-body', [
                    'gas' => $gas,
                    'consumers' => $consumers,
                    'totalUnbilled' => $totalUnbilled
                ]);
        }
        return view('reports.consumer.unbilled-report.list',[
            'gas' => $gas,
            'consumers' => $consumers,
            'totalUnbilled' => $totalUnbilled
        ]);
    } 

    public function list(Request $request)
    {
        // Prepare data
        $sortBy  = $request->get('sortBy', 'cns_consumers.created_at');
        $sortOr  = $request->get('sortOr', 'desc');
        $records = (int) $request->get('records', 20);

        $consumers = Consumer::with([
            'ga:id,name',
            'segment:id,name',
            'connectType:id,name',
            'statusHistory',
            'status:id,name',
            'latestInvoice:id,consumer_id,invoice_date,total_amount,invoice_number,status_id',
            'latestInvoice.status:id,name',
            'latestInvoice.consumption:id,invoice_id,net_consumption'
        ])
         ->leftJoin('bil_invoices', function ($join) {
             $join->on('bil_invoices.consumer_id', '=', 'cns_consumers.id')
                  ->where('bil_invoices.invoice_date', '>=', now()->subDays(60))
                  ->where('bil_invoices.type_id', InvoiceType::GAS_BILL->value);
         })
        ->select([
            'cns_consumers.id','cns_consumers.crn', 'cns_consumers.segment_id', 'cns_consumers.fname', 'cns_consumers.ga_id', 'cns_consumers.status_id', 'cns_consumers.connection_type_id'
        ])
        ->when($request->filled('key'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->whereAny(['crn', 'fname', 'phone'], 'like', '%' . $request->key . '%')
                ->orWhereHas('meter', function ($q1) use ($request) {
                    $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $request->key . '%');
                });
            });
        })
        ->whereIn('cns_consumers.status_id',[ConsumerStatus::ACTIVATE->value])
        ->where('cns_consumers.connection_type_id', ConnectionType::POSTPAID->value)
        ->whereNull('bil_invoices.id')           // No invoice in last 60 days
        ->when($request->filled('geo_area'), fn($q) => $q->whereIn('cns_consumers.ga_id', $request->geo_area))
        ->when($request->filled('invoice_type'), fn($q) => $q->whereIn('bil_invoices.type_id', $request->invoice_type))
        ->when($request->filled('segments'), fn($q) => $q->whereIn('cns_consumers.segment_id', $request->segments))
        ->when($request->filled('connection_type_id'), function ($q) use($request) {
            $q->where('connection_type_id', $request->connection_type_id);
        })
        ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
        // dd($consumers);
        if($request->ajax()) {
            return view('reports.consumer.unbilled-report.consumers-list-body', [
                    'consumers' => $consumers
                ]);
        }
        return view('reports.consumer.unbilled-report.consumers-list',[
            'consumers' => $consumers
        ]);
    }

    /**
     * Consumers Export
     */
    public function listExport(Request $request)
    {
        return (new UnbilledConsumersExport($request))->download('unbilled-consumers.xlsx');
    }
}
?>