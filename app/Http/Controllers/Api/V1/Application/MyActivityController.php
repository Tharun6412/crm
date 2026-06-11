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
     * Index
     * 
     * Dashboard
     */
    public function index(Request $request)
    {
        // USer Gas And Cas
        $user_cas = Auth::user()->cas->pluck('id');
        // Consumer Waiting List for the Responsible User
        $roles = Role::whereIn('id', [EnumsRole::MDPE->value, EnumsRole::GI_ENGINEER->value, EnumsRole::HSE->value, EnumsRole::ACTIVATION->value, EnumsRole::MARKETING->value])
            ->whereIn('id', Auth::user()->roles->pluck('id'))->orderBy('position')->pluck('name', 'id');
        $status_ids = [];
        foreach($roles as $id => $role_name) {
            switch($id) {
                case EnumsRole::MARKETING->value:
                    $status_ids[] = EnumsConsumerStatus::PRE_REGISTER->value;break;
                    // $status_ids[] = EnumsConsumerStatus::REGISTER->value;break;
                case EnumsRole::MDPE->value:
                    $status_ids[] = EnumsConsumerStatus::REGISTER->value;break;
                    // $status_ids[] = EnumsConsumerStatus::ACCEPT->value;break;
                case EnumsRole::GI_ENGINEER->value:
                    // $status_ids[] = EnumsConsumerStatus::EXECUTE->value;
                    $status_ids[] = EnumsConsumerStatus::ACCEPT->value;break;
                case EnumsRole::HSE->value:
                    $status_ids[] = EnumsConsumerStatus::EXECUTE->value;break;
                    // $status_ids[] = EnumsConsumerStatus::HSC->value;break;
                case EnumsRole::ACTIVATION->value:
                    $status_ids[] = EnumsConsumerStatus::HSC->value;break;
                    // $status_ids[] = EnumsConsumerStatus::ACTIVATE->value;break;
                default:
                    $status_ids[] = NULL;break;
            }
        }
        $consumers_count = $consumer_ids = [];
        $consumers_list = Consumer::select('status_id', DB::raw('COUNT(id) as consumer_count'), DB::raw('GROUP_CONCAT(id) as consumer_ids'))
            ->whereIn('ga_id', Auth::user()->ga->pluck('id'))
            ->whereIn('ca_id', $user_cas)
            ->whereIn('status_id', array_unique($status_ids))
            ->groupBy('status_id')->get();
        foreach($consumers_list as $list) {
            $consumers_count[$list->status_id] = $list->consumer_count;
            $consumer_ids[$list->status_id] = explode(",", $list->consumer_ids); 
        }
        $con_ids = array_unique(array_merge(...array_values($consumer_ids)));

        // 2nd Part
        // My Teams Assigned Consumers List
        // Teams List 
        $teams = Team::select('id', 'name', 'department_id')->whereHas('users', function($q) use($request) {
            $q->where('user_id', Auth::id());
        })->get();
        $team_keys = $teams->pluck('id')->toArray();
        // Assigned List
        $assigned_consumers = TeamConsumer::select('status_id', DB::raw('COUNT(id) as assign_count'))->groupBy('status_id')->get()->pluck('assign_count', 'status_id');
        // Pending Consumers List
        $consumer_teams= TeamConsumer::select('status_id','team_id','status', DB::raw('COUNT(id) as team_count'))->whereIn('team_id', $team_keys)->groupBy('status_id','team_id', 'status')->get();
        $consumers_list = $cns_status = [];
        foreach ($consumer_teams as $key => $value) {
            switch($value->status_id) {
                case \App\Enums\ConsumerStatus::PRE_REGISTER->value:
                    $status_id = 1;break;
                case \App\Enums\ConsumerStatus::REGISTER->value:
                    $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;break;
                case \App\Enums\ConsumerStatus::ACCEPT->value:
                    $status_id = \App\Enums\ConsumerStatus::REGISTER->value;break;
                case \App\Enums\ConsumerStatus::EXECUTE->value:
                    $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;break;
                case \App\Enums\ConsumerStatus::HSC->value:
                    $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;break;
                case \App\Enums\ConsumerStatus::ACTIVATE->value:
                    $status_id = \App\Enums\ConsumerStatus::HSC->value;break;
                default:$status_id = NULL;break;
            }
            $consumers_list[$value->team_id][$value->status] = $value->team_count;
        }
        // 3rd Part
        // My Work Report Progress
        // Status List
        $status_list = MasterConsumerStatus::select('id', 'name')->get();
        // Completed consumers List
        $completed_consumers = ConsumerStatus::select('status_id', DB::raw('COUNT(id) as total_count'))->where('created_by', Auth::id())->groupBy('status_id')->orderBy('status_id')->get()->pluck('total_count', 'status_id');
        // Render output
        return response()->json([
            'roles' => $roles,
            'consumers_count' => $consumers_count,
            'teams' => $teams,
            'consumers_list' => $consumers_list,
            'assgined_consumers' => $assigned_consumers,
            'status_list' => $status_list,
            'completed_consumers' => $completed_consumers,
        ], 200);
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