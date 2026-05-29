<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Consumer Waiting Controller
 */
class ConsumerWaitingController extends Controller
{
    /**
     * Index Method
     * @param $request
     * @return view
     */
    public function index(Request $request)
    {
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $segments = Segment::all();
        $connection_types = ConnectionType::all();
        $total_teams = Team::where('status',1)->count();
        // Get all consumer status counts
        $consumer_status = Consumer::selectRaw('ga_id, status_id, count(status_id) as count')
            ->when(($request->has('connect_type_id') AND !empty($request->connect_type_id)), function($q) use($request) {
                $q->where('connection_type_id', $request->connect_type_id);
            })
            ->when(($request->has('onboard_segment_id') AND !empty($request->onboard_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->onboard_segment_id);
            })
            ->groupBy('ga_id', 'status_id')->get();
        
        // Prepare data
        $consumer_wait_list = [];
        $consumer_wait_sum = [];
        foreach($consumer_status as $row) {
            $consumer_wait_list[$row->ga_id][$row->status_id] = $row->count;
            $consumer_wait_sum[$row->status_id] = isset($consumer_wait_sum[$row->status_id]) ? $consumer_wait_sum[$row->status_id] + $row->count : $row->count;
        }
        // Response
        if($request->ajax()) {
            return view('reports.consumer.waiting-report.report-body', [
                'geo_areas' => $geo_areas,
                'segments' => $segments,
                'connection_types' => $connection_types,
                'consumer_wait_list' => $consumer_wait_list,
                'consumer_wait_sum' => $consumer_wait_sum,
                'total_teams' => $total_teams,
            ]);    
        }else {
            return view('reports.consumer.waiting-report.report', [
                'geo_areas' => $geo_areas,
                'segments' => $segments,
                'connection_types' => $connection_types,
                'consumer_wait_list' => $consumer_wait_list,
                'consumer_wait_sum' => $consumer_wait_sum,
                'total_teams' => $total_teams,
            ]);
        }
    }

    /**
     * Employee List Based on Role and Department
     */
    public function consumersListForEmployees(Request $request) 
    {
        // Based on Status
        switch($request->cns_status) {
            case ConsumerStatus::REGISTER->value: //waiting to Accept
                $user_ca_list = $this->getConsumersListByCa($request, $request->ga_id, $request->cns_status, ROLE::MDPE->value);
                $teams = Team::with(['cas:id,name', 'users:id'])->where('ga_id', $request->ga_id)->where('department_id', Department::MDPE->value)->get();
                $status_val = ConsumerStatus::ACCEPT->name;
                break;
            case ConsumerStatus::ACCEPT->value: //waiting to Execute
                $user_ca_list = $this->getConsumersListByCa($request, $request->ga_id, $request->cns_status, ROLE::GI_ENGINEER->value);
                $teams = Team::with(['cas:id,name', 'users:id'])->where('ga_id', $request->ga_id)->where('department_id', Department::GI->value)->get();
                $status_val = ConsumerStatus::EXECUTE->name;
                break;
            case ConsumerStatus::EXECUTE->value: //waiting to HSC
                $user_ca_list = $this->getConsumersListByCa($request, $request->ga_id, $request->cns_status, ROLE::HSE->value);
                $teams = Team::with(['cas:id,name', 'users:id'])->where('ga_id', $request->ga_id)->where('department_id', Department::HSE->value)->get();
                $status_val = ConsumerStatus::HSC->name;
                break;
            case ConsumerStatus::HSC->value: //waiting to Activate
                $user_ca_list = $this->getConsumersListByCa($request, $request->ga_id, $request->cns_status, ROLE::ACTIVATION->value);
                $teams = Team::with(['cas:id,name', 'users:id'])->where('ga_id', $request->ga_id)->where('department_id', Department::ACTIVATION->value)->get();
                $status_val = ConsumerStatus::ACTIVATE->name;
                break;
            default:
                $status_val = null;$user_ca_list = $teams = [];break;
        }
        // Teams By CA
        $team_ca = [];
        if(count($teams) > 0) {
            foreach($teams as $team) {
                foreach ($team->cas as $ca) {
                    $team_ca[$ca->id][] = $team;
                }
            }
        }
        // Response
        return view('reports.consumer.waiting-report.ca-wait-report', [
            'charge_areas' => $user_ca_list['consumers_count'],
            'users_list_ca' => $user_ca_list['users_list_ca'],
            'status_name' => $status_val,
            'team_ca' => $team_ca,
            'ga_name' => Ga::select('id', 'name')->where('id', $request->ga_id)->first(),
        ]);
    }

    /**
     * Common Function
     */
    public function getConsumersListByCa(Request $request, $ga_id, $status, $role)
    {
        // Consumers Count
        $consumers_count = Ca::select('mst_cas.id', 'mst_cas.name', DB::raw('COUNT(cns_consumers.id) as ca_count'))
            ->leftJoin('cns_consumers', function ($join) use ($request, $ga_id, $status) {
                $join->on('cns_consumers.ca_id', '=', 'mst_cas.id')
                    ->where('cns_consumers.ga_id', $ga_id)
                    ->where('cns_consumers.status_id', $status);

                if ($request->filled('connection_type_id')) {
                    $join->where('cns_consumers.connection_type_id', $request->connection_type_id);
                }

                if ($request->filled('segments')) {
                    $join->where('cns_consumers.segment_id', $request->segments);
                }
            })
            ->where('mst_cas.ga_id', $ga_id)
            ->groupBy('mst_cas.id', 'mst_cas.name')
            ->orderByDesc('ca_count')
            ->get();
        // Users List
        $users_list = User::with([
            'cas:id,name',
            'department:id,name',
            'roles:id,name',
        ])->whereHas('ga', function($q) use($ga_id) {
            $q->where('adm_user_ga.ga_id', $ga_id);
        })->whereHas('roles', function($q) use($role) {
            $q->where('role_id', $role);
        })->get();
        // Users List By Charge Area
        $users_list_ca = [];
        foreach ($users_list as $user) {
            foreach ($user->cas as $ca) {
                $users_list_ca[$ca->id][] = $user;
            }
        }
        // Response
        return [
            'consumers_count' => $consumers_count,
            'users_list_ca' => $users_list_ca,
        ];
    }
    /**
     * Ga Wise Teams counts
     */
    public function teams(Request $request)
    {
        $geo_areas = Ga::with('teams')->where('status', 1)->orderBy('position')->get();
        return view('reports.consumer.waiting-report.teams',['geo_areas' => $geo_areas]);
    }
    /**
     * Team list
     */
    public function getTeams(Request $request)
    {
        $teams = Team::with(['users','ga','departments'])->where('ga_id',$request->ga_id)->where('status',1)->get();
        return view('reports.consumer.waiting-report.team-list',['teams' => $teams,'ga_name' => $request->ga_name]);
    }

    /**
     * Get Areas List by CA
     */
    public function getAreasList(Request $request)
    {
        $areas = Area::where('ca_id', $request->ca_id)->get();
        return view('reports.consumer.waiting-report.area-wait-report', ['areas' => $areas]);
    }
}