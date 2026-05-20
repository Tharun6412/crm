<?php
namespace App\Exports\Reports;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Models\Invoice\BillInvoice;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesReportExport implements FromQuery,ShouldQueue,WithChunkReading, WithHeadings, WithMapping 
{
    use Exportable;
    /**
     * Construct Method
     */
    protected $request;
    protected $exportId;
    protected $i = 0;
    public function __construct($request, $exportId)
    {
        $this->request = $request;
        $this->exportId = $exportId;
    }
    /**
    * @return query
    */
    public function query()
    {
        $request = $this->request;
        // $sortBy = ($request['sortBy']) ? $request['sortBy'] : 'bil_invoices.created_at';
        // $sortOr = ($request['sortOr']) ? $request['sortOr'] : 'desc';
        $today = Carbon::today();
        $invoices = BillInvoice::query()->with([
            'invoiceType:id,name',
            'consumer.segment:id,name',
            'consumer.connectType:id,name',
            'consumer.ga:id,name',
            'consumer.district:id,name',
            'status:id,name',
            'consumption:id,invoice_id,net_consumption'
        ])->join('cns_consumers', 'cns_consumers.id', '=', 'bil_invoices.consumer_id')
            ->whereNot('bil_invoices.status_id', InvoiceStatus::CANCEL->value)
            ->when(!empty($request['range']), function ($q) use($request, $today) {
                // Aging Filter
                switch ($request['range']) 
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
            ->when(!empty($request['ga_id']), function ($q) use($request) {
                $q->where('cns_consumers.ga_id', $request['ga_id']);
            })
            ->when(!empty($request['invoice_type']), function ($q) use($request) {
                $q->whereIn('bil_invoices.type_id', $request['invoice_type']);
            })
            ->when((!empty($request['key'])), function($q) use($request) {
                $q->where(function($q) use($request){
                    $q->where('bil_invoices.invoice_number', 'like', '%' . $request['key'] . '%');
                    $q->orWhere('cns_consumers.crn', 'like', '%' . $request['key'] . '%');
                });
            })
            ->when(!empty($request['segments']), function ($q) use($request) {
                $q->whereIn('segment_id', $request['segments']);
            })
            ->when(!empty($request['status_id']), function ($q) use($request) {
                $q->whereIn('bil_invoices.status_id', $request['status_id']);
            })
            ->when(!empty($request['connection_type_id']), function ($q) use($request) {
                $q->whereIn('connection_type_id', $request['connection_type_id']);
            })
            ->when(!empty($request['geo_area']), function ($q) use($request) {
                $q->whereIn('ga_id', $request['geo_area']);
            })
            ->when(!empty($request['district']), function ($q) use($request) {
                $q->whereIn('district_id', $request['district']);
            })
            ->when((!empty($request['date_from']) and !empty($request['date_to'])), function($q) use($request) {
                $q->whereBetween('bil_invoices.invoice_date', [Carbon::createFromFormat('d-m-Y', $request['date_from'])->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request['date_to'])->endOfDay()->toDateTimeString()]);
            })
            ->select('bil_invoices.id','invoice_number','invoice_date','type_id','due_date','total_amount', 'payable_amount', 'balance_amount', 'consumer_id', 'bil_invoices.status_id')
            ->orderBy('bil_invoices.created_at', 'desc');
        return $invoices;
    }

    public function chunkSize(): int
    {
        return 5000;
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
            $invoice->invoice_date ? dateFormat($invoice->invoice_date) : '',
            $invoice->invoiceType?->name,
            $invoice->consumer?->crn,
            $invoice->consumer?->name,
            $invoice->consumer?->segment?->name,
            $invoice->consumer?->connectType?->name,
            $invoice->consumer?->ga?->name,
            $invoice->consumer?->district?->name,
            numberFormat($invoice->consumption?->net_consumption ?? 0, 2),
            $invoice->due_date ? dateFormat($invoice->due_date) : '',
            numberFormat($invoice->payable_amount, 2),
            numberFormat($invoice->balance_amount, 2),
            $invoice->status?->name,
        ];
    }
}
