<?php

namespace App\Exports\Complaints;

use App\Enums\ComplaintStatus;
use App\Models\Complaint\Complaint;
use App\Models\Master\ComplaintCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class ComplaintExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;
    protected $i = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $sortBy = $this->request->get('sortBy', 'created_at');
        $sortOr = $this->request->get('sortOr', 'desc');
        return Complaint::query()
            // JOINS (ONLY ONCE)
            ->leftJoin('cns_consumers', 'cns_consumers.id', '=', 'cmp_complaints.consumer_id')
            ->leftJoin('mst_segments', 'mst_segments.id', '=', 'cmp_complaints.segment_id')
            ->leftJoin('mst_cmp_status', 'mst_cmp_status.id', '=', 'cmp_complaints.status_id')
            ->leftJoin('mst_cmp_priorities', 'mst_cmp_priorities.id', '=', 'cmp_complaints.priority_id')
            ->leftJoin('mst_cmp_categories as sub_cat', 'sub_cat.id', '=', 'cmp_complaints.category_id')
            ->leftJoin('mst_cmp_categories as parent_cat', 'parent_cat.id', '=', 'sub_cat.parent_id')
            ->leftJoin('mst_gas', 'mst_gas.id', '=', 'cmp_complaints.ga_id')
            ->leftJoin('users', 'users.id', '=', 'cmp_complaints.created_by')
            // SELECT ONLY REQUIRED FIELDS
            ->select([
                'cmp_complaints.code',
                'cmp_complaints.created_at',
                'cmp_complaints.closed_at',
                'cmp_complaints.estimated_closed_at',
                'mst_gas.name as ga_name',
                'mst_segments.name as segment_name',
                'mst_cmp_status.name as status_name',
                'mst_cmp_priorities.name as priority_name',
                'parent_cat.name as category_name',
                'sub_cat.name as subcategory_name',
                'sub_cat.resolution_type',
                'cns_consumers.crn',
                'cns_consumers.fname as consumer_name',
                'cmp_complaints.name as manual_name',
                'cmp_complaints.consumer_id',
                'cmp_complaints.created_by',
                DB::raw('CONCAT_WS(" ",users.first_name,users.last_name) as raised_name')
            ])

            ->when($this->request->filled('key'), function ($q) {
                $key = $this->request->key;

                $q->where(function ($query) use ($key) {
                    $query->where('cmp_complaints.code', 'like', "%{$key}%")
                        ->orWhere('cns_consumers.crn', 'like', "%{$key}%")
                        ->orWhere('cns_consumers.fname', 'like', "%{$key}%");
                });
            })
            ->when(!empty($this->request->segment_id), fn($q) =>
                $q->whereIn('cmp_complaints.segment_id', $this->request->segment_id))
            ->when(!empty($this->request->cmp_status), fn($q) =>
                $q->whereIn('cmp_complaints.status_id', $this->request->cmp_status))
            ->when(!empty($this->request->subcategory), fn($q) =>
                $q->whereIn('cmp_complaints.category_id', $this->request->subcategory))
            ->when(!empty($this->request->category) && empty($this->request->subcategory), function ($q) {
                $subIds = ComplaintCategory::whereIn('parent_id', $this->request->category)->pluck('id');
                $q->whereIn('cmp_complaints.category_id', $subIds);
            })
            ->when($this->request->has('pf'), function ($q) {
                $pf = $this->request->pf;
                $q->where('cmp_complaints.status_id', ComplaintStatus::CLOSE->value)
                ->when(in_array(1, $pf) && !in_array(0, $pf),
                    fn($q) => $q->whereHas('feedback')
                )
                ->when(in_array(0, $pf) && !in_array(1, $pf),
                    fn($q) => $q->whereDoesntHave('feedback')
                );
            })
            ->when(!(isAdmin() OR isSuperAdmin() OR isFullAccess()), function ($q) {
                $q->whereIn('cmp_complaints.ga_id', session('user')['gas']);
            })
            ->when(!empty($this->request->user_id), fn($q) => 
                $q->whereIn('cmp_complaints.created_by', $this->request->user_id))
            ->when(!empty($this->request->geo_area), fn($q) =>
                $q->whereIn('cmp_complaints.ga_id', $this->request->geo_area))
            ->when((!empty($this->request->date_from) and !empty($this->request->date_to)), function($q) {
                $q->whereBetween('cmp_complaints.created_at', [Carbon::createFromFormat('d-m-Y', $this->request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $this->request->date_to)->endOfDay()->toDateTimeString()]);
            })
            ->orderBy('cmp_complaints.created_at', 'desc');
    }

    public function headings(): array
    {
        return ['S.No', 'GA', 'Complaint Number', 'Category', 'Sub Category', 'CRN', 'Name', 'Segment', 'Raised Date', 'Raised By', 'Estimated Close Date', 'Closed Date', 'Deviation', 'Priority', 'Status'];
    }

    public function map($row): array
    {
        $this->i++;

        // Time Calculation
        $now = ($row->closed_at) ? $row->closed_at : \Carbon\Carbon::now();
        $estimated = \Carbon\Carbon::parse($row->estimated_closed_at);

        if ($row->resolution_type == 1) {
            $days = abs($now->diffInDays($estimated));
            $difference = ceil($days) . ' days';
        } else {
            $hours = abs($now->diffInHours($estimated)); // numeric only
            if ($hours > 24) {
                $difference = ceil($hours / 24) . ' days';
            } else {
                $difference = numberFormat($hours, 2) . ' hrs';
            }
        }
        //for close no deviation required
        if($now > $row->estimated_closed_at) {
            $deviation_diff = $difference;
        }else {
            $deviation_diff = 0;
        }
        return [
            $this->i,
            $row->ga_name,
            $row->code,
            $row->category_name,
            $row->subcategory_name,
            $row->crn,
            ($row->consumer_id > 0) ? $row->consumer_name : $row->manual_name,
            $row->segment_name,
            dateFormat($row->created_at),
            $row?->raised_name,
            optional($row->estimated_closed_at)->format('d-m-y H:i'),
            dateFormat($row->closed_at),
            $deviation_diff,
            $row->priority_name,
            $row->status_name,
        ];
    }
}
