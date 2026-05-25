<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Illuminate\Http\Request;

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
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::MDPE->value, ROLE::MDPE->value);
                $status_val = ConsumerStatus::ACCEPT->name;
                break;
            case ConsumerStatus::ACCEPT->value: //waiting to Execute
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::GI->value, ROLE::GI_ENGINEER->value);
                $status_val = ConsumerStatus::EXECUTE->name;
                break;
            case ConsumerStatus::EXECUTE->value: //waiting to HSC
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::HSE->value, ROLE::HSE->value);
                $status_val = ConsumerStatus::HSC->name;
                break;
            case ConsumerStatus::HSC->value: //waiting to Activate
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::ACTIVATION->value, ROLE::ACTIVATION->value);
                $status_val = ConsumerStatus::ACTIVATE->name;
                break;
            default:
                $status_val = null;$users_list = [];break;
        }
        // Array Preparation
        // First Prepare Consumer CAs
        $consumerCas = Consumer::where('ga_id', $request->ga_id)->where('status_id', $request->cns_status)->distinct()->pluck('ca_id');
        // Getting teams List based on the Charge Areas.
        $teams = Team::select('adm_teams.id', 'adm_teams.name', 'adm_teams.department_id')
            ->with([
                'cas:id',
                'departments:id,name'
            ])->withCount('users as users_count')
            ->join('adm_team_cas', 'adm_teams.id', '=', 'adm_team_cas.team_id')
            ->whereIn('adm_team_cas.ca_id', $consumerCas)->distinct()->get();
        $teamMap = [];
        // Teams Mapping with Consumers Based on Charge Area
        foreach ($teams as $team) {
            foreach ($team->cas as $ca) {
                $teamMap[$ca->id][$team->department_id][] = $team;
            }
        }
        foreach ($users_list as $user) {
            $userTeams = collect();
            // $consumerCas = $user->consumers->pluck('ca_id')->unique();
            $consumerCas = $user->cas->pluck('id')->unique();
            foreach ($consumerCas as $caId) {
                if (isset($teamMap[$caId][$user->department_id])) {
                    $userTeams = $userTeams->merge($teamMap[$caId][$user->department_id]);
                }
            }
            $user->team = $userTeams->unique('id')->values();
        }
        // Response
        return view('reports.consumer.waiting-report.emp-list', [
            'users_list' => $users_list,
            'status_name' => $status_val,
            'ga_name' => Ga::select('id', 'name')->where('id', $request->ga_id)->first(),
        ]);
    }

    /**
     * Common Function
     */
    public function getUsersListByStatus(Request $request, $ga_id, $status, $department, $role)
    {
        $users = User::select('id', 'first_name', 'last_name', 'department_id', 'type_id')->with([
                'cas',
                'department:id,name',
                'roles:id,name',
                'employeeType:id,name'
            ])
            ->where('department_id', $department)
            ->whereHas('ga', function($q) use($ga_id) {
                $q->where('adm_user_ga.ga_id', $ga_id);
            })
            ->whereHas('roles', function($q) use($role) {
                $q->where('role_id', $role);
            })
            ->withCount(['consumers as count' => function($q) use($status, $ga_id, $request) {
                if(!empty($request->connection_type_id)) {
                    $q->where('connection_type_id', $request->connection_type_id);
                }
                if(!empty($request->segments)) {
                    $q->where('segment_id', $request->segments);
                }
                $q->where('ga_id', $ga_id)->where('status_id', $status);
            }])
            ->get();
        return $users;
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
}