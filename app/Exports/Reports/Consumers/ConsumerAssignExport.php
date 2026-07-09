<?php

namespace App\Exports\Reports\Consumers;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Models\Consumer\Consumer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsumerAssignExport implements FromQuery, WithHeadings, WithMapping 
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
         // Mapping Departments based on Status
        $status_val = $this->request->cns_status[0] ?? '';
        switch($status_val){
            case ConsumerStatus::PRE_REGISTER->value:
                $dept_id = Department::MARKETING->value;
                break;
            case ConsumerStatus::REGISTER->value:
                $dept_id = Department::MARKETING->value;
                break; 
            case ConsumerStatus::ACCEPT->value:
                $dept_id = Department::GI->value;
                break;
            case ConsumerStatus::EXECUTE->value:
                $dept_id = Department::HSE->value;
                break;
            case ConsumerStatus::HSC->value:
                $dept_id = Department::ACTIVATION->value;
                break;
            default :
                $dept_id = $status_val = NUll;
                break;
        }
        // Sub Query
        $latestStatus = DB::table('cns_consumer_status as cs1')
            ->select(
                'cs1.consumer_id',
                'cs1.status_id',
                'cs1.created_at'
            )
            ->whereRaw('cs1.id = (
                SELECT MAX(cs2.id)
                FROM cns_consumer_status cs2
                WHERE cs2.consumer_id = cs1.consumer_id
                AND cs2.status_id = cs1.status_id
            )');
        // Get Consumers List
        $consumers = Consumer::with(['ga', 'ca', 'area','subArea', 'status','teamConsumer.team'])->leftJoin('cns_consumer_teams', function ($join) {
            $join->on('cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
                ->whereIn('cns_consumer_teams.status_id', $this->request->target_status);
            })
            ->leftJoinSub($latestStatus, 'latest_status', function($join) {
                $join->on('cns_consumers.id', '=', 'latest_status.consumer_id')->on('latest_status.status_id', '=', 'cns_consumers.status_id');
            })
            ->leftJoin('lms_teams', 'lms_teams.id', '=', 'cns_consumer_teams.team_id')
            ->leftJoin('users', 'users.id', '=', 'cns_consumer_teams.created_by')
            ->leftJoin('users as assign_user', 'assign_user.id', '=', 'cns_consumer_teams.assign_to')
            ->leftJoin('mst_cns_status', 'mst_cns_status.id', '=', 'cns_consumer_teams.status_id')
            ->select(
                'cns_consumers.id','cns_consumers.fname','cns_consumers.lname','cns_consumers.crn','cns_consumers.t_crn','cns_consumers.ga_id','cns_consumers.ca_id','cns_consumers.area_id','cns_consumers.subarea_id','cns_consumers.status_id',
                'lms_teams.name as team_name',
                'cns_consumer_teams.status as team_status',
                'mst_cns_status.name as status_name',
                DB::raw('CONCAT_WS(" ", users.first_name, users.last_name) as team_created_by'),
                DB::raw('CONCAT_WS(" ", assign_user.first_name, assign_user.last_name) as assign_name'),
                'cns_consumer_teams.assign_to',
                'cns_consumer_teams.status_id as team_status_id',
                DB::raw('DATEDIFF(CURDATE(), latest_status.created_at) as ageing_days'),
            )
            ->when($this->request->filled('key'), function($q) {
                $q->where('cns_consumers.t_crn','like','%'.$this->request->key.'%')->orWhere('cns_consumers.crn','like','%'.$this->request->key.'%');
            })
            ->when(!empty($this->request->team_id), function ($q) {
                $q->whereIn('team_id', $this->request->team_id);
            })
            ->when($this->request->has('user_id'), function ($q) {
                $q->where('cns_consumer_teams.created_by', $this->request->user_id);
            })
            ->when($this->request->has('status'), function ($q) {
                if (in_array(2, $this->request->status)) {
                    // Unassigned
                    $q->whereNull('cns_consumer_teams.id')
                        ->when($this->request->has('cns_status'), function ($q1) {
                                $q1->whereIn('cns_consumers.status_id', $this->request->cns_status);
                        });
                } else {
                    // Assigned / Completed
                    $q->whereNotNull('cns_consumer_teams.id')->whereIn('cns_consumer_teams.status', $this->request->status)
                        ->when($this->request->has('target_status'), function ($q1) {
                                $q1->whereIn('cns_consumer_teams.status_id', $this->request->target_status);
                        });
                }
            })
            ->when($this->request->has('ugas'), function ($q) {
                $q->whereIn('cns_consumers.ga_id', $this->request->ugas);
            })
            ->when($this->request->has('ucas'), function($q) {
                $q->whereIn('cns_consumers.ca_id', $this->request->ucas);
            })
            ->when($this->request->has('area_ids'), function($q) {
                $q->whereIn('cns_consumers.area_id', $this->request->area_ids);
            })
            ->when($this->request->has('geo_area'), function ($q) {
                $q->whereIn('cns_consumers.ga_id', $this->request->geo_area);
            })
            ->when($this->request->has('charge_area'), function($q) {
                $q->whereIn('cns_consumers.ca_id', $this->request->charge_area);
            })
            ->when($this->request->has('area'), function($q) {
                $q->whereIn('cns_consumers.area_id', $this->request->area);
            })
            ->when($this->request->has('subarea'), function($q) {
                $q->whereIn('cns_consumers.subarea_id', $this->request->subarea);
            })
            ->orderBy($sortBy, $sortOr);
        return $consumers;
    }

    /**
     * Headings 
     */
    public function headings():array
    {
        return ['S.No', 'CRN', 'Name', 'GA', 'Charge Area', 'Area', 'Sub Area', 'Status', 'Days', 'Team', 'Assign To', 'Assign Work Status', 'status', 'Assign By'];
    }

    /**
     * Mapping [Loop the data from the query]
     */
    public function map($consumer): array
    {
        if (is_null($consumer->team_status)) {
            $sts_val = 'Not Assigned';
        } elseif ($consumer->team_status == 0) {
            $sts_val = 'Assigned';
        } elseif ($consumer->team_status == 1) {
            $sts_val = 'Completed';
        } else {
            $sts_val = 'Not Assigned';
        }
        $this->i++; //Increment serial Number
        return [
            $this->i,
            $consumer->crn ?? $consumer->t_crn,
            $consumer?->name,
            $consumer->ga->name,
            $consumer->ca->name,
            $consumer->area?->name,
            $consumer->subArea?->name,
            $consumer->status->name,
            $consumer?->ageing_days,
            $consumer->team_name,
            $consumer?->assign_name,
            $consumer?->status_name,
            $sts_val,
            $consumer?->team_created_by,
        ];
    }
}
