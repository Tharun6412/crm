<?php

namespace App\Exports\Reports;

use App\Enums\ConsumerStatus;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerScheme;
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
        $sd_amounts = Consumer::with([
            'scheme:id,consumer_id,scheme_id,total_deposit,paid_deposit,balance',
            'ga:id,name',
            'segment:id,name',
            'scheme.scheme:id,name',
            'connectType:id,name'
        ])
        ->whereHas('status', function($query) use ($status) {
            $query->where('status_id', $status);
        })
        ->when($this->request->filled('geo_area') || $this->request->filled('segments') || $this->request->filled('connection_type_id') ||$this->request->filled('status'), function ($q) {
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
        })
        ->when($this->request->filled('amount_range'), function ($q) {
            $q->whereHas('scheme', function ($query) {
                if ($this->request->amount_range == "5000+") {
                    $query->where('balance', '>=', 5000);
                } else {
                    [$min, $max] = explode('-', $this->request->amount_range);
                    $query->whereBetween('balance', [$min, $max]);
                }
            });
        })
        ->when($this->request->filled('key'), function ($q) {
            $q->where(function ($query) {
                $query->whereAny(['crn', 'created_at'], 'like', '%' . $this->request->key . '%')
                    ->orWhereHas('scheme', function ($q) {
                        $q->whereAny(['total_deposit', 'paid_deposit', 'balance'], 'like', '%' . $this->request->key . '%');
                });
            });
        })
        ->when($this->request->filled('scheme'), function ($q) {
            $q->whereHas('scheme', function($query) {
                $query->whereIn('scheme_id', $this->request->scheme);
            });
        })
        ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
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
        // Condition for status
        if(request()->status == ConsumerStatus::PRE_REGISTER->value || request()->status == null) {
            $status_val = 1;
        }else {
            $status_val = 2;
        }
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $amount->crn ?? '',
            $amount->ga->name ?? '',
            $amount->status->name ?? '',
            $amount->segment->name ?? '',
            $amount->connectType->name ?? '',
            $amount->scheme->scheme->name ?? '',
            $amount->scheme->total_deposit ?? 0,
            $amount->scheme->paid_deposit ?? 0,
            $amount->scheme->balance ?? 0,
            $amount->statusHistory->where('status_id', $status_val)->first()->created_at->format('d-m-Y'),
        ];
    }
}
