<?php
namespace App\Exports\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesReportExport implements FromQuery, WithHeadings, WithMapping 
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
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'bil_invoices.created_at';
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
                $q->whereIn('segment_id', $request->segments);
            })
             ->when($request->filled('status_id'), function ($q) use($request) {
                $q->whereIn('bil_invoices.status_id', $request->status_id);
            })
            ->when($request->filled('connection_type_id'), function ($q) use($request) {
                $q->whereIn('connection_type_id', $request->connection_type_id);
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
            ->orderBy($sortBy, $sortOr);
        return $invoices;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'Invoice Number', 'Invoice Date', 'Invoice Type', 'CRN', 'Name', 'Segment','Connection Type','GA','District','Consumption', 'Due Date', 'Invoice Amount', 'Balance Amount', 'Payment Status'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($invoice): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $invoice->invoice_number ?? '',
            dateFormat($invoice->invoice_date) ?? '',
            $invoice->invoiceType->name,
            $invoice->consumer->crn,
            $invoice->consumer->name,
            $invoice->consumer->segment->name,
            $invoice->consumer->connectType->name,
            $invoice->consumer->ga->name,
            $invoice->consumer->district->name,
            numberFormat($invoice->net_consumption,2),
            dateFormat($invoice->due_date),
            numberFormat($invoice->payable_amount),
            numberFormat($invoice->balance_amount),
            $invoice->status->name,
        ];
    }
}
