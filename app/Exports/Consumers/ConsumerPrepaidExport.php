<?php

namespace App\Exports\Consumers;

use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\Prepaid;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumerPrepaidExport implements FromQuery, WithHeadings, WithMapping 
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
        // Get data
        $consumers = Prepaid::with([
                'consumers.ga',
                'consumers.status',
                'consumers.createdBy'
            ])
            ->whereBetween('conversion_date', [$from, $to])
            ->whereHas('consumers', function ($q) {
                $q->where('ga_id', $this->request->ga_id);    
                if ($this->request->filled('conv_segment_id')) {
                    $q->where('segment_id', $this->request->conv_segment_id);
                }
            })->orderBy('created_at', 'desc');
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'GA','CRN', 'Connection Type', 'Name', 'Status', 'Conversion Date', 'Updated By', 'Created Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer->consumers->ga?->name,
            $consumer->consumers?->crn ?? $consumer->consumers->t_crn,
            $consumer->consumers->connectType?->name,
            $consumer->consumers?->name,
            $consumer->consumers->status?->name,
            $consumer->conversion_date ? dateFormat($consumer->conversion_date) : '',
            $consumer->consumers->createdBy?->name,
            $consumer->consumers->created_at ? dateFormat($consumer->consumers->created_at) : '',
        ];
    }
}
