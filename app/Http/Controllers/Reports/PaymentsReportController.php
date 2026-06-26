<?php

namespace App\Http\Controllers\Reports;

use App\Enums\PaymentStatus;
use App\Exports\Reports\PaymentsReportExport;
use App\Http\Controllers\Controller;
use App\Jobs\AfterExportJob;
use App\Models\Invoice\InvoicePayment;
use App\Services\UserExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class PaymentsReportController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'pay_invoice_payments.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        // Get all receipts
        $payments_qry = InvoicePayment::with([
                'invoice:id,invoice_number,invoice_date,type_id,consumer_id',
                'invoice.invoiceType:id,name',
                'invoice.consumer:id,crn,fname,lname,segment_id,connection_type_id,ga_id',
                'invoice.consumer.segment:id,name',
                'invoice.consumer.connectType:id,name',
                'invoice.consumer.ga:id,name',
                'paymentType:id,name'
            ])
            ->select(['id', 'code', 'payment_date', 'amount', 'payment_type_id', 'invoice_id'])
            ->whereNot('status_id', PaymentStatus::REVERSAL->value)
            ->when($request->filled('key'), function($q) use($request){
                $q->when(function($q) use ($request){
                    $q->where('code', 'like', '%'.$request->key.'%')
                    ->orWhereHas('invoice', fn ($q) => $q->where('invoice_number', 'like', '%' . $request->key . '%'))
                    ->orWhereHas('invoice.consumer', fn ($q) => $q->where('crn', 'like', '%' . $request->key . '%'));
                });
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('payment_date', [
                    Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(),
                    Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()
                ]);
            })
            ->when($request->has('payment_type'), function($q) use($request) {
                $q->whereIn('payment_type_id', $request->payment_type);
            })
            ->when($request->has('invoice_type'), function($q) use($request) {
                $q->whereHas('invoice', fn ($q) => $q->whereIn('type_id', $request->invoice_type));
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereHas('invoice.consumer', fn ($q) => $q->whereIn('ga_id', $request->geo_area));
            });
            // ->when($request->has('segments'), function($q) use($request){
            //     $q->whereIn('cns_consumers.segment_id', $request->segments);
            // })
            // ->when($request->has('connection_type_id'), function($q) use($request){
            //     $q->whereIn('cns_consumers.connection_type_id', $request->connection_type_id);
            // })
            // ->orderBy($sortBy)
        $tRecords = (clone $payments_qry)->count();
        $tAmount  = (clone $payments_qry)->sum('amount'); // <-- grand total
        $payments = $payments_qry->orderBy('id')->cursorPaginate($records)->withQueryString();
        // Render output
        if ($request->ajax()) {
            return view('reports.payments.payment-report.list-body', ['payments' => $payments, 'tRecords' => $tRecords,'tAmount'  => $tAmount]);
        }
        return view('reports.payments.payment-report.list', ['payments' => $payments, 'tRecords' => $tRecords, 'tAmount'  => $tAmount,]);
    }

    /**
     * Export the payments.
     */
    public function paymentsReportExport(Request $request)
    {
        // return (new PaymentsReportExport($request))->download('PaymentsReport.xlsx');
         $filename = 'payment_report_' . time() . '.csv';

        // Add export job with export service
        $export_id = UserExportService::create($filename);
        // Check export limit
        if($export_id) {
            // Queue the export and attach AfterExportJob to run AFTER storage
            Excel::queue(new PaymentsReportExport($request->all(), $export_id->id), $filename, 'public')
                ->chain([new AfterExportJob($export_id->id)]);
        }
        // response in modal
        return view('admin.exports.create', ['export_id' => $export_id]);
    }
}