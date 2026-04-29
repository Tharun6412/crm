<?php

namespace App\Exports\Consumers;

use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumerOnboardExport implements FromQuery, WithHeadings, WithMapping 
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
        // Prepare params
        $from = Carbon::parse($this->request->date_from)->startOfDay();
        $to   = Carbon::parse($this->request->date_to)->endOfDay();
        $status_date = NULL;
        if($this->request->filled('status_date')) {
            $status_date = Carbon::parse($this->request->status_date)->startOfDay();
        }
        // Get data
        $consumers = ConsumerStatus::whereHas('consumer', function($q) use($status_date) {
                if($this->request->filled('connection_type_id')) {
                    $q->where('connection_type_id', $this->request->connection_type_id);
                }
                if($this->request->filled('segment_id')) {
                    $q->where('segment_id', $this->request->segment_id);
                }
                if($this->request->filled('ga_id')) {
                    $q->where('ga_id', $this->request->ga_id);
                }
                if(!empty($status_date)) {
                    $q->where('created_at','>=', $status_date);
                }
            })
            ->whereBetween('created_at', [$from, $to])
            ->where('status_id', $this->request->status_id)->orderBy('created_at', 'desc');
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'GA','CRN', 'Connection Type', 'Name', 'Status', 'Status Date', 'Updated By', 'Created Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer->consumer->ga?->name,
            $consumer->consumer?->crn ?? '',
            $consumer->consumer->connectType?->name,
            $consumer->consumer?->name,
            $consumer->status?->name,
            $consumer->created_at ? dateFormat($consumer->created_at) : '',
            $consumer->createdBy?->name,
            $consumer->consumer->created_at ? dateFormat($consumer->consumer->created_at) : '',
        ];
    }
}
