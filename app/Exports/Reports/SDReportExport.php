<?php

namespace App\Exports\Reports;

use App\Enums\ConsumerStatus;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerScheme;
use App\Models\Consumer\ConsumerStatus as ConsumerConsumerStatus;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SDReportExport implements FromQuery, WithHeadings, WithMapping
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
        $status = $this->request->filled('status') ? $this->request->status : 1;
        // Get Security Deposit Details
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $sd_amounts = ConsumerConsumerStatus::with([
            'consumer.scheme:id,consumer_id,scheme_id,total_deposit,paid_deposit,balance',
            'consumer.ga:id,name',
            'consumer.segment:id,name',
            'consumer.scheme.scheme:id,name',
            'consumer.connectType:id,name',
            'status:id,name'
        ])
        ->where('status_id', $status)
        ->when($this->request->filled('date_from') && $this->request->filled('date_to'), function ($q) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()]);
        })
        ->whereHas('consumer', function ($q) {
            // GA restriction
            if (!isAdmin() && !isSuperAdmin()) {
                $q->whereIn('ga_id', session('user')['gas']);
            }
            if ($this->request->filled('geo_area')) {
                $q->whereIn('ga_id', $this->request->geo_area);
            }
            if ($this->request->filled('connection_type_id')) {
                $q->whereIn('connection_type_id', $this->request->connection_type_id);
            }
            if ($this->request->filled('segments')) {
                $q->whereIn('segment_id', $this->request->segments);
            }
            // Scheme filter
            if ($this->request->filled('scheme')) {
                $q->whereHas('scheme', function ($query) {
                    $query->whereIn('scheme_id', $this->request->scheme);
                });
            }
            // Amount filter (balance inside scheme)
            if ($this->request->filled('amount_range')) {
                $q->whereHas('scheme', function ($query) {
                    if ($this->request->amount_range == "5000+") {
                        $query->where('balance', '>=', 5000);
                    } else {
                        [$min, $max] = explode('-', $this->request->amount_range);
                        $query->whereBetween('balance', [$min, $max]);
                    }
                });
            }
        })->orderBy($sortBy, $sortOr);
    return $sd_amounts;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'GA', 'status', 'Segment', 'Connection Type', 'Scheme', 'Total Deposit', 'Paid Deposit', 'Balance', 'Created Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($amount): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $amount->consumer->crn ?? '',
            $amount->consumer->ga->name ?? '',
            $amount->consumer->status->name ?? '',
            $amount->consumer->segment->name ?? '',
            $amount->consumer->connectType->name ?? '',
            $amount->consumer->scheme->scheme->name ?? '',
            $amount->consumer->scheme->total_deposit ?? 0,
            $amount->consumer->scheme->paid_deposit ?? 0,
            $amount->consumer->scheme->balance ?? 0,
            $amount->created_at?->format('d-m-Y'),
        ];
    }
}
