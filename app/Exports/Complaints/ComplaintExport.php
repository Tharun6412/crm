<?php

namespace App\Exports\Complaints;

use App\Models\Complaint\Complaint;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComplaintExport implements FromQuery, WithHeadings, WithMapping 
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
        $complaints = Complaint::when($this->request->has('key'), function ($q) {
                $q->whereAny(['code'], 'like', '%' . $this->request->key . '%');
            })
            ->when($this->request->has('cmp_status'), function($q) {
                $q->whereIn('status_id', $this->request->cmp_status);
            })
            ->orderBy($sortBy, $sortOr);
        return $complaints;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'GA', 'Complaint Number', 'Category', 'CRN', 'Name', 'Segment', 'Estimated Close Date', 'Closed Date', 'Priority', 'Status', 'Added Date'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($complaint): array
    {
        $this->i++; //Increment serial Number
        $now = Carbon::now();
        if ($complaint->category->resolution_type == 1) {
            $difference = ceil(abs($now->diffInDays(\Carbon\Carbon::parse($complaint->estimated_closed_at))))."D";
        }else {
            $difference = numberFormat(abs($now->diffInHours(\Carbon\Carbon::parse($complaint->estimated_closed_at))), 2)."H";
        }
        return [
            $this->i,
            $complaint->ga->name ?? '',
            $complaint->code,
            $complaint->category->name,
            $complaint->consumer->crn,
            ($complaint->consumer_id > 0) ? $complaint->consumer->name : $complaint->name,
            $complaint->segment->name,
            $complaint->estimated_closed_at?->format('d-m-y H:i')." ".$difference,
            dateFormat($complaint->closed_at) ?? '',
            $complaint->priority->name,
            $complaint->status->name,
            dateFormat($complaint->created_at),
        ];
    }
}
