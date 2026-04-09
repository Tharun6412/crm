<?php

namespace App\Exports\Complaints;

use App\Models\Complaint\Complaint;
use App\Models\Master\ComplaintCategory;
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
        $complaints = Complaint::when((!isAdmin() AND !isSuperAdmin()), function ($q) {
                $q->whereIn('ga_id', session('user')['gas']);
            })
            ->when($this->request->filled('key'), function ($q) {
                $q->whereAny(['code'], 'like', '%' . $this->request->key . '%');
                $q->orWhereHas('consumer', function ($subQuery) {
                    $subQuery->where('crn', 'like', '%' . $this->request->key . '%')
                            ->orWhere('name', 'like', '%' . $this->request->key . '%');
                });
            })
            ->when($this->request->has('segment_id'), function($q) {
                $q->whereIn('segment_id', $this->request->segment_id);
            })
            ->when($this->request->has('cmp_status'), function($q) {
                $q->whereIn('status_id', $this->request->cmp_status);
            })
            ->when($this->request->filled('subcategory'), function($q) {
                $q->whereIn('category_id', $this->request->subcategory);
            })
            ->when($this->request->filled('category') && !$this->request->filled('subcategory'), function($q) {
                $subIds = ComplaintCategory::whereIn('parent_id', $this->request->category)->pluck('id');
                $q->whereIn('category_id', $subIds);
            })
            ->when($this->request->has('geo_area'), function($q) {
                $q->whereIn('ga_id', $this->request->geo_area);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->orderBy($sortBy, $sortOr);
        return $complaints;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'GA', 'Complaint Number', 'Category', 'Sub Category', 'CRN', 'Name', 'Segment', 'Raised Date' , 'Estimated Close Date', 'Closed Date', 'Deviation', 'Priority', 'Status'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($complaint): array
    {
        $this->i++; //Increment serial Number
        $now = Carbon::now();
        if ($complaint->category?->resolution_type == 1) {
            $difference = ceil(abs($now->diffInDays(\Carbon\Carbon::parse($complaint->estimated_closed_at))))."D";
        }else {
            $difference = numberFormat(abs($now->diffInHours(\Carbon\Carbon::parse($complaint?->estimated_closed_at))), 2)."H";
        }
        return [
            $this->i,
            $complaint->ga->name ?? '',
            $complaint->code,
            $complaint->category?->parent->name,
            $complaint->category?->name,
            $complaint->consumer->crn,
            ($complaint->consumer_id > 0) ? $complaint->consumer->name : $complaint->name,
            $complaint->segment->name,
            dateFormat($complaint->created_at),
            $complaint->estimated_closed_at?->format('d-m-y H:i'),
            dateFormat($complaint->closed_at) ?? '',
            $difference,
            $complaint->priority?->name,
            $complaint->status?->name,
        ];
    }
}
