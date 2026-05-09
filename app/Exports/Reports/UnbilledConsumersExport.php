<?php

namespace App\Exports\Reports;

use App\Enums\ConnectionType;
use App\Enums\ConsumerStatus;
use App\Enums\InvoiceType;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus as ConsumerConsumerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UnbilledConsumersExport implements FromQuery, WithHeadings, WithMapping 
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
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'cns_consumers.created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $consumers = Consumer::query()->with([
            'ga:id,name',
            'ca:id,name',
            'segment:id,name',
            'district:id,name',
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
            'cns_consumers.id','cns_consumers.crn', 'cns_consumers.segment_id', 'cns_consumers.fname', 'cns_consumers.ga_id', 'cns_consumers.status_id', 'cns_consumers.district_id', 'cns_consumers.ca_id', 'cns_consumers.hno', 'cns_consumers.street','cns_consumers.colony', 'cns_consumers.city', 'cns_consumers.ward'
        ])
        ->when($this->request->filled('key'), function ($q) {
            $q->where(function ($query) {
                $query->whereAny(['crn', 'fname', 'phone'], 'like', '%' . $this->request->key . '%')
                ->orWhereHas('meter', function ($q1) {
                    $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $this->request->key . '%');
                });
            });
        })
        ->whereIn('cns_consumers.status_id',[ConsumerStatus::ACTIVATE->value])
        ->where('cns_consumers.connection_type_id', ConnectionType::POSTPAID->value)
        ->whereNull('bil_invoices.id')           // No invoice in last 60 days
        ->when($this->request->filled('geo_area'), fn($q) => $q->whereIn('cns_consumers.ga_id', $this->request->geo_area))
        ->when($this->request->filled('invoice_type'), fn($q) => $q->whereIn('bil_invoices.type_id', $this->request->invoice_type))
        ->when($this->request->filled('segments'), fn($q) => $q->whereIn('cns_consumers.segment_id', $this->request->segments))
        ->when($this->request->filled('connection_type_id'), function ($q) {
            $q->where('connection_type_id', $this->request->connection_type_id);
        })
        ->orderBy($sortBy, $sortOr);
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'Name', 'Segment', 'Status', 'GA', 'District', 'CA', 'Hno', 'Street','Colony','City','Ward', 'Status Date', 'Invoice Date', 'Invoice Number', 'Consumption', 'Invoice Amount', 'Invoice Status'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        // Latest Invoice 
        $latest_inv = $consumer->latestInvoice()->latest()->first();
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer->crn ?? '',
            $consumer->name,
            $consumer->segment->name,
            $consumer->status->name,
            $consumer->ga->name,
            $consumer->district->name,
            $consumer->ca->name,
            $consumer->hno,
            $consumer->street,
            $consumer->colony,
            $consumer->city,
            $consumer->ward,
            $consumer?->statusHistory->first()?->created_at?->format('d-m-Y'),
            $latest_inv?->invoice_date?->format('d-m-Y'),
            $latest_inv?->invoice_number,
            numberFormat($latest_inv?->consumption?->net_consumption, 2),
            numberFormat($latest_inv?->payable_amount, 2),
            $latest_inv?->status?->name,
        ];
    }
}
