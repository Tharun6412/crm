<?php

namespace App\Exports\Reports\Consumers;

use App\Enums\ConsumerStatus;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus as ConsumerConsumerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $consumers = Consumer::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
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
            ->when($this->request->has('segments'), function ($q) {
                $q->whereIn('segment_id', $this->request->segments);
            })
            ->when($this->request->has('connection_type_id'), function ($q) {
                $q->where('connection_type_id', $this->request->connection_type_id);
            })
            ->when($this->request->has('geo_area'), function ($q) {
                $q->whereIn('ga_id', $this->request->geo_area);
            })
            ->when($this->request->has('district'), function ($q) {
                $q->whereIn('district_id', $this->request->district);
            })
            ->when($this->request->has('charge_area'), function ($q) {
                $q->whereIn('ca_id', $this->request->charge_area);
            })
            ->when($this->request->has('area'), function ($q) {
                $q->whereIn('area_id', $this->request->area);
            })
            ->when($this->request->has('scheme'), function ($q) {
                $q->where(function($query) {
                    $query->whereHas('scheme', function($q1) {
                        $q1->whereIn('scheme_id', $this->request->scheme);
                    });
                });
            })
            ->when($this->request->has('cns_status'), function ($q) {
                $q->whereIn('status_id', $this->request->cns_status);
            })
            ->when($this->request->has('status_id'), function($q) {
                $q->where('status_id', $this->request->status_id);
            })
            ->when($this->request->filled('range') && $this->request->filled('type'), function ($q) {
                $regSub = ConsumerConsumerStatus::select( 'consumer_id', DB::raw('MIN(created_at) as register_date'))
                    ->where('status_id', ConsumerStatus::REGISTER->value)
                    ->groupBy('consumer_id');
                $q->leftJoinSub($regSub, 'reg', function ($join) {
                    $join->on('cns_consumers.id', '=', 'reg.consumer_id');
                });
                if ($this->request->type == 1) {
                    switch ($this->request->range) {
                        case '90-':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) <= 90');
                            break;
                        case '90-120':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) BETWEEN 91 AND 120');
                            break;
                        case '120+':
                            $q->whereRaw('DATEDIFF(NOW(), reg.register_date) > 120');
                            break;
                    }
                }
                if ($this->request->type == 2) {
                    $actSub = ConsumerConsumerStatus::select('consumer_id',DB::raw('MIN(created_at) as activation_date'))
                        ->where('status_id', ConsumerStatus::ACTIVATE->value)
                        ->groupBy('consumer_id');
                    $q->leftJoinSub($actSub, 'act', function ($join) {
                        $join->on('cns_consumers.id', '=', 'act.consumer_id');
                    });
                    switch ($this->request->range) {
                        case '90-':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) <= 90');
                            break;
                        case '90-120':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) BETWEEN 91 AND 120');
                            break;
                        case '120+':
                            $q->whereRaw('DATEDIFF(act.activation_date, reg.register_date) > 120');
                            break;
                    }
                }
            })->orderBy($sortBy, $sortOr);
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'Connection Type', 'Name', 'Segment', 'Status', 'GA', 'Scheme', 'Added Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer->crn ?? '',
            $consumer->connectType->name,
            $consumer->name,
            $consumer->segment->name,
            $consumer->status->name,
            $consumer->ga->name,
            $consumer->scheme->scheme->name,
            dateFormat($consumer->created_at),
        ];
    }
}
