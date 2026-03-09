<?php
namespace App\Exports\Reports;

use App\Enums\PaymentStatus;
use App\Models\Invoice\InvoicePayment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsReportExport implements FromQuery, WithHeadings, WithMapping 
{
    use Exportable;
    /**
     * Construct Method
     */
    protected $request;
    protected $i = 0;
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
    * @return query
    */
    public function query()
    {
        $request = $this->request;
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'pay_invoice_payments.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 20;
        $payments = InvoicePayment::leftJoin('bil_invoices', 'bil_invoices.id', '=', 'pay_invoice_payments.invoice_id')
            ->leftJoin('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->whereNot('pay_invoice_payments.status_id', PaymentStatus::REVERSAL->value)
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
            ->orderBy($sortBy, $sortOr);
        return $payments;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'Payment Code','Payment Date','Amount','Payment Type','Invoice Number', 'Invoice Date', 'Invoice Type', 'CRN', 'Name', 'Segment','Connection Type','GA'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($payment): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $payment->code ?? '',
            dateFormat($payment->payment_date) ?? '',
            $payment->amount ?? '',
            $payment->paymentType->name ?? '',
            $payment->invoice->invoice_number ?? '',
            dateFormat($payment->invoice->invoice_date) ?? '',
            $payment->invoice->invoiceType->name,
            $payment->invoice->consumer->crn,
            $payment->invoice->consumer->name,
            $payment->invoice->consumer->segment->name,
            $payment->invoice->consumer->connectType->name,
            $payment->invoice->consumer->ga->name,
        ];
    }
}
