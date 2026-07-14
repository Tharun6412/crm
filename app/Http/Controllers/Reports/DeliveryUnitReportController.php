<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\TeamConsumer;
use App\Models\Lms\DeliveryUnit;
use App\Models\Lms\Team;
use App\Models\Master\MasterConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryUnitReportController extends Controller
{
    // Index method
    public function index(Request $request)
    {
        // Validation
        $request->validate([
            'geo_area' => 'required',
            'du_date_from' => 'required',
            'du_date_to' => 'required',
        ]);
        // Get GA Delivery Units
        $delivery_units = DeliveryUnit::with(['areas'])
            ->when($request->has('departments'), function($q) use($request) {
                $q->whereIn('dept_id', $request->departments);
            })
            ->whereIn('ga_id', $request->geo_area)->orderBy('ga_id', 'desc')->get();
        // Assigned and Completed List of Consumers
        $assign_list = [];
        $assigned = TeamConsumer::leftJoin('lms_teams', 'lms_teams.id', '=', 'cns_consumer_teams.team_id')
            ->leftJoin('lms_delivery_units', 'lms_delivery_units.id', '=', 'lms_teams.du_id')
            ->select('cns_consumer_teams.status', 'lms_teams.du_id', DB::raw('COUNT(cns_consumer_teams.id) as total_count'))
            ->whereBetween('cns_consumer_teams.created_at', [Carbon::createFromFormat('d-m-Y', $request->du_date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->du_date_to)->endOfDay()])
            ->whereIn('lms_delivery_units.ga_id', $request->geo_area)
            ->groupBy('lms_teams.du_id', 'cns_consumer_teams.status')->get();
        foreach($assigned as $assign_val) {
            $assign_list[$assign_val->du_id][$assign_val->status] = $assign_val->total_count;
        }
        // dd($assign_list);
        $status_list = MasterConsumerStatus::whereIn('id', [ConsumerStatus::ACTIVATE->value, ConsumerStatus::ACCEPT->value, ConsumerStatus::EXECUTE->value, ConsumerStatus::HSC->value])->get();
        // Response
        return view('reports.delivery-units.list', [
            'delivery_units' => $delivery_units,
            'status_list' => $status_list,
            'assign_list' => $assign_list,
        ]);
    }

    /**
     * Get Team Wise Consumers List from Selected Delivery Unit
     * @param $delivery_unit_id
     */
    public function getDeliveryUnitTeamsList(Request $request, $du_id)
    {
        $teams = Team::where('du_id', $du_id)->get();
        // Assigned and Completed Consumers Count
        $consumers_count = TeamConsumer::leftJoin('lms_teams', 'lms_teams.id', '=', 'cns_consumer_teams.team_id')
            ->leftJoin('lms_delivery_units', 'lms_delivery_units.id', '=', 'lms_teams.du_id')
            ->select('cns_consumer_teams.status', 'cns_consumer_teams.team_id', DB::raw('COUNT(cns_consumer_teams.id) as team_count'))
            ->whereBetween('cns_consumer_teams.created_at', [Carbon::createFromFormat('d-m-Y', $request->du_date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->du_date_to)->endOfDay()])
            ->where('lms_teams.du_id', $du_id)->groupBy('cns_consumer_teams.team_id', 'cns_consumer_teams.status')->get();
        $team_consumers = [];
        foreach($consumers_count as $count) {
            $team_consumers[$count->team_id][$count->status] = $count->team_count;
        }
        return view('reports.delivery-units.teams-list', [
            'teams' => $teams, 
            'team_consumers' => $team_consumers,
            'unassigned' => $request->unassigned ?? 0,
            'du_id' => $du_id,
        ]);
    }

    /**
     * Delivery Unit Teams Assigned Count
     */
    public function getDuTeamsAssigned(Request $request, $du_id)
    {
        $teams = Team::where('du_id', $du_id)->get();
        // Assigned and Completed Consumers Count
        $consumers_count = TeamConsumer::leftJoin('lms_teams', 'lms_teams.id', '=', 'cns_consumer_teams.team_id')
            ->leftJoin('lms_delivery_units', 'lms_delivery_units.id', '=', 'lms_teams.du_id')
            ->select('cns_consumer_teams.status', 'cns_consumer_teams.team_id', DB::raw('COUNT(cns_consumer_teams.id) as team_count'))
            ->where('lms_teams.du_id', $du_id)->groupBy('cns_consumer_teams.team_id', 'cns_consumer_teams.status')->get();
        $team_consumers = [];
        foreach($consumers_count as $count) {
            $team_consumers[$count->team_id][$count->status] = $count->team_count;
        }
        return view('reports.delivery-units.waiting-report.du-teams-assigned', [
            'teams' => $teams, 
            'team_consumers' => $team_consumers,
            // 'total_count' => $request->total,
        ]);
    }

    /**
     * Get All Delivery Unit Progress
     */
    public function getDeliveryUnitProgress(Request $request)
    {
        // dd($request->all());
        // Validation
        $request->validate([
            'geo_area' => 'required',
        ]);
        // Get GA Delivery Units
        $delivery_units = DeliveryUnit::with(['areas'])
            ->when($request->filled('department_id'), function($q) use($request) {
                $q->where('dept_id', $request->department_id);
            })
            ->whereIn('ga_id', $request->geo_area)->orderBy('ga_id', 'desc')->get();
        // Get Consumers count Based on Areas
        $consumer_counts = DB::table('cns_consumers')
            ->join('lms_du_areas', 'cns_consumers.area_id', '=', 'lms_du_areas.area_id')
            ->join('lms_delivery_units', 'lms_du_areas.du_id', '=', 'lms_delivery_units.id')
            ->select(
                'lms_delivery_units.id as du_id',
                'lms_delivery_units.responsible_status_id',
                DB::raw('COUNT(cns_consumers.id) as total')
            )
            ->whereIn('cns_consumers.ga_id', $request->geo_area)
            ->whereColumn(
                'cns_consumers.status_id',
                'lms_delivery_units.responsible_status_id'
            )
            ->groupBy(
                'lms_delivery_units.id',
                'lms_delivery_units.responsible_status_id'
            )
            ->get();
        $count = [];
        foreach ($consumer_counts as $row) {
            $count[$row->du_id] = [
                'status_id' => $row->responsible_status_id,
                'total' => $row->total,
            ];
        }
        $status_list = MasterConsumerStatus::whereIn('id', [ConsumerStatus::ACTIVATE->value, ConsumerStatus::ACCEPT->value, ConsumerStatus::EXECUTE->value, ConsumerStatus::HSC->value])->get();
        // Response
        return view('reports.delivery-units.waiting-report.list', [
            'delivery_units' => $delivery_units,
            'count' => $count,
            'status_list' => $status_list,
        ]);
    }

}