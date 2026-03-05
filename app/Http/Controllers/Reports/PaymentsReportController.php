<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Invoice\InvoicePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentsReportController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'pay_invoice_payments.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 20;
        $payments = InvoicePayment::leftJoin('bil_invoices', 'bil_invoices.id', '=', 'pay_invoice_payments.invoice_id')
            ->leftJoin('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->when($request->filled('key'), function($q) use($request){
                $q->when(function($q) use ($request){
                    $q->where('pay_invoice_payments.code', 'like', '%'.$request->key.'%');
                    $q->orWhere('bil_invoices.invoice_number', 'like', '%'.$request->key.'%');
                    $q->orWhere('cns_consumers.crn','like', '%'.$request->key.'%');
                });
            })
            ->when($request->has('payment_type'), function($q) use($request) {
                $q->whereIn('pay_invoice_payments.payment_type_id',$request->payment_type);
            })
            ->when($request->has('invoice_type'), function($q) use($request) {
                $q->whereIn('bil_invoices.type_id', $request->invoice_type);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('pay_invoice_payments.payment_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->when($request->has('segments'), function($q) use($request){
                $q->whereIn('cns_consumers.segment_id', $request->segments);
            })
            ->when($request->has('connection_type_id'), function($q) use($request){
                $q->whereIn('cns_consumers.connection_type_id', $request->connection_type_id);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('cns_consumers.ga_id', $request->geo_area);
            })
            ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        // Render output
        if ($request->ajax()) {
            return view('reports.payments.payment-report.list-body', ['payments' => $payments]);
        }
        return view('reports.payments.payment-report.list', ['payments' => $payments]);
    }
}