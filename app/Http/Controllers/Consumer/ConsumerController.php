<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\AwsPath;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Exports\Consumers\ConsumerExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerDocument;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\InvoicePayment;
use App\Models\Master\MasterConsumerStatus;
use App\Models\Master\Title;
use App\Models\Payments\PayAdvanceTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumerController extends Controller
{
    /**
     * Index
     * 
     * ConsumerStatus will map the URL slug with database and returns object
     */
    public function index(Request $request, MasterConsumerStatus $status)
    {
        // Get consumers
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        // Query
        $consumers = Consumer::with([
                'segment',
                'status',
                'ga',
                'district',
                'scheme.scheme'
            ])
            ->when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($request->filled('key'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereAny(['t_crn', 'crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $request->key . '%')
                    ->orWhereHas('meter', function ($q1) use ($request) {
                        $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $request->key . '%');
                    });
                });
            })
            ->when($request->has('segments'), fn ($q) => $q->whereIn('segment_id', $request->segments))
            ->when($request->filled('connection_type_id'), fn ($q) => $q->where('connection_type_id', $request->connection_type_id))
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('district'), function ($q) use($request) {
                $q->whereIn('district_id', $request->district);
            })
            ->when($request->has('charge_area'), function ($q) use($request) {
                $q->whereIn('ca_id', $request->charge_area);
            })
            ->when($request->has('area'), function ($q) use($request) {
                $q->whereIn('area_id', $request->area);
            })
            ->when($request->has('scheme'), function ($q) use($request) {
                $q->where(function($query) use($request) {
                    $query->whereHas('scheme', function($q1) use($request) {
                        $q1->whereIn('scheme_id', $request->scheme);
                    });
                });
            })
            ->when($request->has('cns_status'), function ($q) use($request) {
                $q->whereIn('status_id', $request->cns_status);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->when(($status->id != null), function($q) use($status) {
                $q->where('status_id', $status->id);
            })
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
        // Render output
        if($request->ajax()) {
            return view('consumers.consumers.list-body', ['consumers' => $consumers, 'status' => $status]);
        }
        else {
            return view('consumers.consumers.list', ['consumers' => $consumers, 'status' => $status]);
        }
    }

    /**
     * Show Consumer details
     * @param int $id
     */
    public function show($id)
    {
        // Find Consumer
        $consumer = Consumer::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })->find($id);
        // Documents
        $documents_list = ConsumerDocument::where('consumer_id', $id)->get();
        // Abort if consumer not found
        if (! $consumer) {
            abort(403, 'Consumer data not found');
        }

        // Render output
        return view('consumers.consumers.show', [
            'consumer' => $consumer,
            'consumer_meter' => $consumer->meter->where('status', 1)->first(),
            'documents_list' => $documents_list,
        ]);
    }

    /**
     * Consumer Documents
     * @param int $id
     */
    public function consumerDocs(Request $request, $id)
    {
        $documents_list = ConsumerDocument::where('consumer_id', $id)->get()->groupBy('status_id');
        return view('consumers.consumers.show-documents', ['documents_list' => $documents_list]);
    }
    /**
     * Consumers Export
     */
    public function consumerExport(Request $request)
    {
        return (new ConsumerExport($request))->download('consumers.csv');
    }

    /**
     * Ledger Report from Invoices and Payments
     * @param $consumer_id
     */
    public function ledgerReport(Request $request, $id)
    {
        // Get Invoices
        $invoices = BillInvoice::selectRaw("
            'Debit' as type,
            id as inv_id,
            invoice_date,
            invoice_number,
            advance_amount,
            payable_amount,
            type_id,
            created_at
        ")
        ->where('status_id','!=',InvoiceStatus::CANCEL->value)
        ->where('consumer_id', $id);
        // Get Payments
        $payments = InvoicePayment::selectRaw("
                'Credit' as type,
                bil_invoices.id as inv_id,
                pay_invoice_payments.payment_date as invoice_date,
                bil_invoices.invoice_number as invoice_number,
                0 as advance_amount,
                pay_invoice_payments.amount as payable_amount,
                bil_invoices.type_id as type_id,
                pay_invoice_payments.created_at
            ")
            ->leftJoin('bil_invoices', 'bil_invoices.id', '=', 'pay_invoice_payments.invoice_id')
            ->where('pay_invoice_payments.status_id','!=',PaymentStatus::REVERSAL->value)
            ->where('bil_invoices.consumer_id', $id);
        // Join Queries using Union All 
        $ledger_report = $invoices->unionAll($payments)->orderBy('created_at', 'asc')->get();
        return view('consumers.consumers.show-ledger-report', ['ledger_report' => $ledger_report]);
    }

    /**
     * Consumer Advance Payment
     */
    // public function consumerAdvanceTransaction(Request $request, $id)
    // {
    //     $transactions = InvoicePayment::whereHas('invoice', function($q) use($id) {
    //         $q->where(['consumer_id' => $id, 'type_id' => InvoiceType::GAS_BILL->value]);
    //     })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

    //     // Render output
    //     return view('consumers.consumers.show-advance-transactions', [
    //         'transactions' => $transactions,
    //     ]);
    // }
}