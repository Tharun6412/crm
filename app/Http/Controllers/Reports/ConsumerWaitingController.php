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

class ConsumerWaitingController extends Controller
{
    /**
     * Index
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
     * Employee List 
     */
    public function consumersListForEmployees(Request $request) 
    {
        switch($request->cns_status) {
            case ConsumerStatus::PRE_REGISTER->value: //Waiting to Register
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::OM->value, ROLE::EMPLOYEE->value);
                $status_val = ConsumerStatus::REGISTER->name;
                break;
            case ConsumerStatus::REGISTER->value: //waiting to Accept
                $users_list = $this->getUsersListByStatus($request, $request->ga_id, $request->cns_status, Department::OM->value, ROLE::EMPLOYEE->value);
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
                $status_val = null;
                $users_list = User::with(['teams', 'ca', 'teams.cas','department', 'consumers'])
                    ->whereHas('ga', function($q) use($request) {
                        $q->where('adm_user_ga.ga_id', $request->ga_id);
                    })
                    ->get();
                foreach ($users_list as $user) {
                    // Status Mapping
                    $statuses = match ($user->department_id) {
                        Department::OM->value => [ConsumerStatus::PRE_REGISTER->value, ConsumerStatus::REGISTER->value],
                        Department::GI->value => [ConsumerStatus::ACCEPT->value],
                        Department::HSE->value => [ConsumerStatus::EXECUTE->value],
                        Department::ACTIVATION->value => [ConsumerStatus::HSC->value],
                        default => []
                    };
                    $user->count = $user->consumers()->where('ga_id', $request->ga_id)->whereIn('status_id', $statuses)->count();

                }
            }
        // $status_val = $request->status+1;
        // Response
        return view('reports.consumer.waiting-report.emp-list', [
            'users_list' => $users_list,
            'status_name' => $status_val,
            'ga_name' => Ga::where('id', $request->ga_id)->value('name'),
        ]);
    }

    /**
     * Common Function
     */
    public function getUsersListByStatus(Request $request, $ga_id, $status, $department, $role)
    {
        $users = User::with(['teams', 'ca', 'teams.cas','department', 'consumers'])
            ->where('department_id', $department)
            ->whereHas('ga', function($q) use($ga_id) {
                $q->where('adm_user_ga.ga_id', $ga_id);
            })
            ->whereHas('roles', function($q) use($role) {
                $q->where('role_id', $role);
            })
            ->withCount(['consumers as count' => function($q) use($status, $ga_id, $request) {
                if(!empty($request->connect_type_id)) {
                    $q->where('connection_type_id', $request->connect_type_id);
                }
                if(!empty($request->onboard_segment_id)) {
                    $q->where('segment_id', $request->onboard_segment_id);
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