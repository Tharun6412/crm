<?php

namespace App\Exports\Consumers;

use App\Models\Consumer\Consumer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumerExport implements FromQuery, WithHeadings, WithMapping 
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
        $sortBy = ($this->request->get('sortBy')) ? $this->request->get('sortBy') : 'created_at';
        $sortOr = ($this->request->get('sortOr')) ? $this->request->get('sortOr') : 'desc';
        $consumers = Consumer::with([
                'segment:id,name',
                'connectType:id,name',
                'ga:id,name',
                'district:id,name',
                'ca:id,name',
                'area:id,name',
                'status:id,name',
            ])->when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($this->request->filled('key'), function ($q) {
                $q->where(function ($query) {
                    $query->whereAny(['crn', 'fname', 'lname', 'email', 'phone'], 'like', '%' . $this->request->key . '%')
                    ->orWhereHas('meter', function ($q1) {
                        $q1->whereAny(['meter_no', 'meter_serial_no'], 'like', '%' . $this->request->key . '%');
                    });
                });
            })
            ->when(!empty($this->request->segments), function ($q) {
                $q->whereIn('segment_id', $this->request->segments);
            })
            ->when(!empty($this->request->connection_type_id), function ($q) {
                $q->where('connection_type_id', $this->request->connection_type_id);
            })
            ->when(!empty($this->request->geo_area), function ($q) {
                $q->whereIn('ga_id', $this->request->geo_area);
            })
            ->when(!empty($this->request->district), function ($q) {
                $q->whereIn('district_id', $this->request->district);
            })
            ->when(!empty($this->request->charge_area), function ($q) {
                $q->whereIn('ca_id', $this->request->charge_area);
            })
            ->when(!empty($this->request->area), function ($q) {
                $q->whereIn('area_id', $this->request->area);
            })
            ->when(!empty($this->request->scheme), function ($q) {
                $q->where(function($query) {
                    $query->whereHas('scheme', function($q1) {
                        $q1->whereIn('scheme_id', $this->request->scheme);
                    });
                });
            })
            ->when(!empty($this->request->cns_status), function ($q) {
                $q->whereIn('status_id', $this->request->cns_status);
            })
            ->when((!empty($this->request->date_from) and !empty($this->request->date_to)), function($q) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->when(!empty($this->request->status_id), function($q) {
                $q->where('status_id', $this->request->status_id);
            })
            ->orderBy($sortBy, $sortOr);
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'Connection Type', 'Name', 'Segment', 'Status', 'GA', 'Scheme', 'Status Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer?->crn ?? '',
            $consumer->connectType?->name,
            $consumer?->name,
            $consumer->segment?->name,
            $consumer->status?->name,
            $consumer->ga?->name,
            $consumer->scheme?->scheme?->name,
            $consumer->created_at ? dateFormat($consumer->created_at) : '',
        ];
    }
}
