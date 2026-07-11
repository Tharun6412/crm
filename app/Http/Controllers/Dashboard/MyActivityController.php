<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Role as EnumsRole;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Lms\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use App\Models\Lms\DeliveryUnit;
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
        // Consumer Waiting List for the Responsible User
        $delivery_units = DeliveryUnit::with(['areas'])->where('manager_id', $user->id)->get();
        $consumers_count = [];
        $user_gas = $user->ga->pluck('id');
        foreach ($delivery_units as $du) {
            $areaIds = $du->areas->pluck('id');
            // Run only if areas available
            if ($areaIds->isNotEmpty()) {
                $consumers_list_data = Consumer::select('status_id', DB::raw('COUNT(id) as consumer_count'))
                    ->whereIn('ga_id', $user_gas)
                    ->whereIn('area_id', $areaIds)
                    ->where('status_id', $du->responsible_status_id)
                    ->groupBy('status_id')
                    ->get();
                foreach ($consumers_list_data as $list) {
                    $consumers_count[$du->id][$list->status_id] = $list->consumer_count;
                }
            }
        }
        // Assigned List
        $assign_list = [];
        $assigned_consumers = TeamConsumer::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
            ->join('lms_teams', 'lms_teams.id', '=', 'cns_consumer_teams.team_id')
            ->join('lms_delivery_units', 'lms_delivery_units.id', '=', 'lms_teams.du_id')
            ->whereIn('cns_consumers.ga_id', $user_gas)
            ->where('cns_consumer_teams.created_by', $user->id)
            ->select('lms_delivery_units.id as du_id','cns_consumer_teams.status_id','cns_consumer_teams.status', DB::raw('COUNT(cns_consumer_teams.id) as assign_count'))->groupBy('lms_delivery_units.id','status_id', 'status')->get();
        foreach($assigned_consumers as $assign) {
            $assign_list[$assign->du_id][$assign->status_id][$assign->status] = $assign->assign_count;
        }
        
        // 2. Teams List and Get the Consumers Count Pending and Completed
        // Get Teams List
        $teams = Team::with(['departments:id,name'])->whereHas('users', function($q) use($user) {
            $q->where('user_id', $user->id);
        })->where('status', 1)->get();
        $team_keys = $teams->pluck('id')->toArray();
        // Pending Consumers List
        $cns_status = $consumers_list = [];
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
            'consumers_count' => $consumers_count,
            'teams' => $teams,
            'completed_consumers' => $completed_consumers,
            'assign_list' => $assign_list,
            'status_list' => $status_list,
            'consumers_list' => $consumers_list,
            'cns_status' => $cns_status,
            'delivery_units' => $delivery_units,
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