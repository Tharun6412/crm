<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role as EnumsRole;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use App\Models\Master\MasterConsumerStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyActivityController extends Controller
{
    /**
     * Dashbaord
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // Consumer Waiting List for the Responsible User
        $role_status = $team_status = false;
        $roles = $user->roles()->whereIn('adm_roles.id', [EnumsRole::GI_ENGINEER->value, EnumsRole::HSE->value, EnumsRole::ACTIVATION->value, EnumsRole::MARKETING->value])
            ->orderBy('position')->pluck('adm_roles.name', 'adm_roles.id');
        $userRoleIds = $roles->keys()->toArray();
        // dd($userRoleIds);
        $roleData = [];
        // 1.Get Consumers Waiting List Based on Roles
        if($roles->isNotEmpty()) {
            $role_status = true;
            // Call function to get My Responsible Consumers List
            $roleData = $this->myConsumersList($user, $roles);           
        }
        // Filter the User Roles Only From RoleData Array
        $filteredRoleData = collect($roleData)->filter(fn ($row) => in_array($row['role_id'], $userRoleIds))->values();

        // 2. Teams List and Get the Consumers Count Pending and Completed
        $teams = Team::with(['departments:id,name'])->whereHas('users', function($q) use($user) {
            $q->where('user_id', $user->id);
        })->get();
        $team_data = [];
        if($teams->isNotEmpty()) {
            $team_status = true;
            $team_data = $this->myAssignedTeams($teams);
        }
        // 3.Get Login User Work Progress

        // Completed consumers List
        $completed_consumers = ConsumerStatus::select('status_id', DB::raw('COUNT(id) as total_count'))->where('created_by', $user->id)->groupBy('status_id')->get()->pluck('total_count', 'status_id');
        // Status List
        $status_list = MasterConsumerStatus::select('id', 'name')->get();
        // Api Response for MyActivity
        return response()->json([
            'role_status' => $role_status,
            'role_data' => $filteredRoleData,
            'team_status' => $team_status,
            'team_data' => $team_data,
            'status_list' => $status_list,
            'completed_consumers' => $completed_consumers,
        ], 200);
    }

    /**
     * Get Consumer Unassigned List
     */
    public function myConsumersList($user, $roles)
    {
        // USer Gas And Cas
        $user_cas = $user->cas->pluck('id');
        $usergas = $user->ga->pluck('id')->toArray();
        $status_ids = $consumers_count = $assign_list = [];
        // Mapping Status based on Roles
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
                    break;
            }
        }
        // Consumer Count Based on Status
        $consumers_list_data = Consumer::select('status_id', DB::raw('COUNT(id) as consumer_count'))
            ->whereIn('ga_id', $usergas)
            ->whereIn('ca_id', $user_cas)
            ->whereIn('status_id', array_unique($status_ids))
            ->groupBy('status_id')->get();
        foreach($consumers_list_data as $list) {
            $consumers_count[$list->status_id] = $list->consumer_count;
        }
        // Assigned List
        $assigned_consumers = TeamConsumer::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
            ->whereIn('cns_consumers.ga_id', $usergas)
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
                'current_status' => EnumsConsumerStatus::PRE_REGISTER->value,
                'target_status' => EnumsConsumerStatus::REGISTER->value,
                'pending' => $consumers_count[EnumsConsumerStatus::PRE_REGISTER->value] ?? 0,
                'assigned' => $assign_list[EnumsConsumerStatus::REGISTER->value][0] ?? 0,
                'completed' => $assign_list[EnumsConsumerStatus::REGISTER->value][1] ?? 0,
                'unassigned' => max(0,($consumers_count[EnumsConsumerStatus::PRE_REGISTER->value] ?? 0) - ($assign_list[EnumsConsumerStatus::REGISTER->value][0] ?? 0)),
            ],
            [
                'role_id' => EnumsRole::MARKETING->value,
                'status' => 'ACCEPTANCE',
                'current_status' => EnumsConsumerStatus::REGISTER->value,
                'target_status' => EnumsConsumerStatus::ACCEPT->value,
                'pending' => $consumers_count[EnumsConsumerStatus::REGISTER->value] ?? 0,
                'assigned' => $assign_list[EnumsConsumerStatus::ACCEPT->value][0] ?? 0,
                'completed' => $assign_list[EnumsConsumerStatus::ACCEPT->value][1] ?? 0,
                'unassigned' => max(0, ($consumers_count[EnumsConsumerStatus::REGISTER->value] ?? 0) - ($assign_list[EnumsConsumerStatus::ACCEPT->value][0] ?? 0)),
            ],
            [
                'role_id' => EnumsRole::GI_ENGINEER->value,
                'status' => 'EXECUTION',
                'current_status' => EnumsConsumerStatus::ACCEPT->value,
                'target_status' => EnumsConsumerStatus::EXECUTE->value,
                'pending' => $consumers_count[EnumsConsumerStatus::ACCEPT->value] ?? 0,
                'assigned' => $assign_list[EnumsConsumerStatus::EXECUTE->value][0] ?? 0,
                'completed' => $assign_list[EnumsConsumerStatus::EXECUTE->value][1] ?? 0,
                'unassigned' => max(0, ($consumers_count[EnumsConsumerStatus::ACCEPT->value] ?? 0) - ($assign_list[EnumsConsumerStatus::EXECUTE->value][0] ?? 0)),
            ],
            [
                'role_id' => EnumsRole::HSE->value,
                'status' => 'HSC',
                'current_status' => EnumsConsumerStatus::EXECUTE->value,
                'target_status' => EnumsConsumerStatus::HSC->value,
                'pending' => $consumers_count[EnumsConsumerStatus::EXECUTE->value] ?? 0,
                'assigned' => $assign_list[EnumsConsumerStatus::HSC->value][0] ?? 0,
                'completed' => $assign_list[EnumsConsumerStatus::HSC->value][1] ?? 0,
                'unassigned' => max(0, ($consumers_count[EnumsConsumerStatus::EXECUTE->value] ?? 0) - ($assign_list[EnumsConsumerStatus::HSC->value][0] ?? 0)),
            ],
            [
                'role_id' => EnumsRole::ACTIVATION->value,
                'status' => 'ACTIVATION',
                'current_status' => EnumsConsumerStatus::HSC->value,
                'target_status' => EnumsConsumerStatus::ACTIVATE->value,
                'pending' => $consumers_count[EnumsConsumerStatus::HSC->value] ?? 0,
                'assigned' => $assign_list[EnumsConsumerStatus::ACTIVATE->value][0] ?? 0,
                'completed' => $assign_list[EnumsConsumerStatus::ACTIVATE->value][1] ?? 0,
                'unassigned' => max(0, ($consumers_count[EnumsConsumerStatus::HSC->value] ?? 0) - ($assign_list[EnumsConsumerStatus::ACTIVATE->value][0] ?? 0)),
            ],
        ];
        return $roleData;
    }

    /**
     * My Assigned Team List with Pending and completed consumers 
     */
    public function myAssignedTeams($teams)
    {
        $team_data = [];
        $team_keys = $teams->pluck('id')->toArray();
        // Pending Consumers List
        $consumer_teams= TeamConsumer::select('team_id','status', DB::raw('COUNT(id) as team_count'))->whereIn('team_id', $team_keys)->groupBy('team_id', 'status')->get();
        $teamCounts = [];
        foreach ($consumer_teams as $row) {
            $teamCounts[$row->team_id][$row->status] = $row->team_count;
        }
        // Mapping User Assigned Teams and display Pending and Completed consumers in team
        foreach($teams as $team_key => $team_val) {
            $team_data[] = array(
                'id' => $team_val->id,
                'name' => $team_val->name,
                'status' => $team_val->status,
                'department' => $team_val->departments?->name,
                'pending' => $teamCounts[$team_val->id][0] ?? 0,
                'completed' => $teamCounts[$team_val->id][1] ?? 0,
            );
        }
        return $team_data;
    }
}