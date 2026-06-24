<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role as EnumsRole;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use App\Models\Master\Ca;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyActivityController extends Controller
{
    /**
     * Index
     * 
     * Dashboard
     */
    public function index(Request $request)
    {
        // USer Gas And Cas
        $user = Auth::user();
        $user_cas = $user->cas->pluck('id');
        // Consumer Waiting List for the Responsible User
        $roles = $user->roles()->whereIn('adm_roles.id', [EnumsRole::GI_ENGINEER->value, EnumsRole::HSE->value, EnumsRole::ACTIVATION->value, EnumsRole::MARKETING->value])
            ->orderBy('position')->pluck('adm_roles.name', 'adm_roles.id');
        // $roles = Role::whereIn('id', [EnumsRole::GI_ENGINEER->value, EnumsRole::HSE->value, EnumsRole::ACTIVATION->value, EnumsRole::MARKETING->value])
        //     ->whereIn('id', Auth::user()->roles->pluck('id'))->orderBy('position')->pluck('name', 'id');
        $status_ids = $consumers_count = $assign_list = $consumers_list = $cns_status = [];
        foreach($roles as $id => $role_name) {
            switch($id) {
                case EnumsRole::MARKETING->value:
                    $status_ids[] = EnumsConsumerStatus::PRE_REGISTER->value;
                    $status_ids[] = EnumsConsumerStatus::REGISTER->value;break;
                case EnumsRole::GI_ENGINEER->value:
                    $status_ids[] = EnumsConsumerStatus::ACCEPT->value;break;
                case EnumsRole::HSE->value:
                    $status_ids[] = EnumsConsumerStatus::EXECUTE->value;break;
                case EnumsRole::ACTIVATION->value:
                    $status_ids[] = EnumsConsumerStatus::HSC->value;break;
                default:
                    $status_ids[] = NULL;break;
            }
        }
        $consumers_list_data = Consumer::select('status_id', DB::raw('COUNT(id) as consumer_count'))
            ->whereIn('ga_id', $user->ga->pluck('id'))
            ->whereIn('ca_id', $user_cas)
            ->whereIn('status_id', array_unique($status_ids))
            ->groupBy('status_id')->get();
        foreach($consumers_list_data as $list) {
            $consumers_count[$list->status_id] = $list->consumer_count;
        }
        // Assigned List
        $assigned_consumers = TeamConsumer::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
            ->whereIn('cns_consumers.ga_id', $user->ga->pluck('id')->toArray())
            ->where('cns_consumer_teams.created_by', $user->id)
            ->select('cns_consumer_teams.status_id','cns_consumer_teams.status', DB::raw('COUNT(cns_consumer_teams.id) as assign_count'))->groupBy('status_id', 'status')->get();
        foreach($assigned_consumers as $assign) {
            $assign_list[$assign->status_id][$assign->status] = $assign->assign_count;
        }
        //Array Preparation For Assigned and UnAssigned
        $roleData = [
            [
                'role_id' => EnumsRole::MARKETING->value,
                'status' => 'REGISTRATION',
                'pending' => $consumers_count[EnumsConsumerStatus::PRE_REGISTER->value] ?? 0,
                'status_id' => EnumsConsumerStatus::PRE_REGISTER->value,
                'consumers_pending' => $assign_list[EnumsConsumerStatus::REGISTER->value][0] ?? 0,
                'consumers_completed' => $assign_list[EnumsConsumerStatus::REGISTER->value][1] ?? 0,
                'unassigned' => ($consumers_count[EnumsConsumerStatus::PRE_REGISTER->value] ?? 0) - ($assign_list[EnumsConsumerStatus::REGISTER->value][0] ?? 0),
            ],
            [
                'role_id' => EnumsRole::MARKETING->value,
                'status' => 'ACCEPTANCE',
                'pending' => $consumers_count[EnumsConsumerStatus::REGISTER->value] ?? 0,
                'status_id' => EnumsConsumerStatus::REGISTER->value,
                'consumers_pending' => $assign_list[EnumsConsumerStatus::ACCEPT->value][0] ?? 0,
                'consumers_completed' => $assign_list[EnumsConsumerStatus::ACCEPT->value][1] ?? 0,
                'unassigned' => ($consumers_count[EnumsConsumerStatus::REGISTER->value] ?? 0) - ($assign_list[EnumsConsumerStatus::ACCEPT->value][0] ?? 0),
            ],
            [
                'role_id' => EnumsRole::GI_ENGINEER->value,
                'status' => 'EXECUTION',
                'pending' => $consumers_count[EnumsConsumerStatus::ACCEPT->value] ?? 0,
                'status_id' => EnumsConsumerStatus::ACCEPT->value,
                'consumers_pending' => $assign_list[EnumsConsumerStatus::EXECUTE->value][0] ?? 0,
                'consumers_completed' => $assign_list[EnumsConsumerStatus::EXECUTE->value][1] ?? 0,
                'unassigned' => ($consumers_count[EnumsConsumerStatus::ACCEPT->value] ?? 0) - ($assign_list[EnumsConsumerStatus::EXECUTE->value][0] ?? 0),
            ],
            [
                'role_id' => EnumsRole::HSE->value,
                'status' => 'HSC',
                'pending' => $consumers_count[EnumsConsumerStatus::EXECUTE->value] ?? 0,
                'status_id' => EnumsConsumerStatus::EXECUTE->value,
                'consumers_pending' => $assign_list[EnumsConsumerStatus::HSC->value][0] ?? 0,
                'consumers_completed' => $assign_list[EnumsConsumerStatus::HSC->value][1] ?? 0,
                'unassigned' => ($consumers_count[EnumsConsumerStatus::EXECUTE->value] ?? 0) - ($assign_list[EnumsConsumerStatus::HSC->value][0] ?? 0),
            ],
            [
                'role_id' => EnumsRole::ACTIVATION->value,
                'status' => 'ACTIVATION',
                'pending' => $consumers_count[EnumsConsumerStatus::HSC->value] ?? 0,                    
                'status_id' => EnumsConsumerStatus::HSC->value,
                'consumers_pending' => $assign_list[EnumsConsumerStatus::ACTIVATE->value][0] ?? 0,
                'consumers_completed' => $assign_list[EnumsConsumerStatus::ACTIVATE->value][1] ?? 0,
                'unassigned' => ($consumers_count[EnumsConsumerStatus::HSC->value] ?? 0) - ($assign_list[EnumsConsumerStatus::ACTIVATE->value][0] ?? 0),
            ],
        ];
        $userRoleIds = $roles->keys()->toArray();
        $filteredRoleData = collect($roleData)->filter(fn ($row) => in_array($row['role_id'], $userRoleIds))->values();
        
        // 2. Teams List and Get the Consumers Count Pending and Completed
        // Get Teams List
        $teams = Team::with(['departments:id,name'])->whereHas('users', function($q) use($user) {
            $q->where('user_id', $user->id);
        })->get();
        $team_keys = $teams->pluck('id')->toArray();
        // Pending Consumers List
        $consumer_teams= TeamConsumer::select('status_id','team_id','status', DB::raw('COUNT(id) as team_count'))->whereIn('team_id', $team_keys)->groupBy('status_id','team_id', 'status')->get();
        foreach($consumer_teams as $key => $value) {
            $consumers_list[$value->team_id][$value->status] = ($consumers_list[$value->team_id][$value->status] ?? 0) + $value->team_count;
            $cns_status[$value->team_id] = array_values(array_unique(array_merge($cns_status[$value->team_id] ?? [],[$value->status_id])));
        }

        // 3.Get Login User Work Progress
        // Completed consumers List
        $completed_consumers = ConsumerStatus::select('status_id', DB::raw('COUNT(id) as total_count'))->where('created_by', $user->id)->groupBy('status_id')->get()->pluck('total_count', 'status_id');
        // Status List
        $status_list = MasterConsumerStatus::all();
        // Render output
        return view('dashboard.my-activity', [
            'roles' => $roles,
            'consumers_count' => $consumers_count,
            'teams' => $teams,
            'completed_consumers' => $completed_consumers,
            'assign_list' => $assign_list,
            'status_list' => $status_list,
            'filteredRoleData' => $filteredRoleData,
            'consumers_list' => $consumers_list,
            'cns_status' => $cns_status,
        ]);
    }
    /**
     * Get Consumers by Charge Areas
     */
    public function getConsumersByCa(Request $request)
    {
        // dd($request->all());
        // Consumers Count
        $charge_areas = Ca::with(['ga:id,name'])->select('mst_cas.ga_id','mst_cas.id', 'mst_cas.name', DB::raw('COUNT(cns_consumers.id) as ca_count'))
            ->leftJoin('cns_consumers', function ($join) use ($request) {
                $join->on('cns_consumers.ca_id', '=', 'mst_cas.id')
                    ->whereIn('cns_consumers.ga_id', Auth::user()->ga->pluck('id'))
                    ->whereIn('cns_consumers.ca_id', Auth::user()->ca->pluck('id'))
                    ->where('cns_consumers.status_id', $request->cns_status);
            })
            ->whereIn('mst_cas.ga_id', $request->geo_area)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('cns_consumer_teams')
                    ->whereColumn('cns_consumer_teams.consumer_id', 'cns_consumers.id');
            })
            ->groupBy('mst_cas.id', 'mst_cas.name')
            ->orderByDesc('ca_count')
            ->get();
        return view('dashboard.ca-unassigned-report', ['charge_areas' => $charge_areas]);
    }

    /**
     * My work report
     */
    public function myConsumersList(Request $request)
    {
        // Get data
        $reports = ConsumerStatus::where('created_by', $request->user_id)->where('status_id', $request->status_id)->orderBy('created_at', 'desc')->paginate(50)->withQueryString();      
        return view('dashboard.consumers-status-list', ['reports' => $reports]);
    }
}