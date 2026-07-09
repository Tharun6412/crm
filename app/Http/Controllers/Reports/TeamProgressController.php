<?php
namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\Department;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Lms\Team;
use App\Models\Admin\User;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Consumer\TeamConsumer;
use App\Models\Master\MasterConsumerStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Employee Activity Controller
 */
class TeamProgressController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        $request->validate([
            'geo_area' => 'required',
            'team_date_from' => 'required',
            'team_date_to' => 'required',
        ]);
        // Get Consumer Teams Assigned List
        $assigned_consumers = TeamConsumer::select('team_id', 'status', DB::raw('COUNT(id) as total_count'))->whereHas('team', function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->team_date_from)->startOfDay(), Carbon::createFromFormat('d-m-Y', $request->team_date_to)->endOfDay()])
            ->groupBy('team_id', 'status')->get();
        $consumer_team_assign = [];
        foreach($assigned_consumers as $assign) {
            $consumer_team_assign[$assign->team_id][$assign->status] = $assign->total_count;
        }
        // Get Teams List
        $teams = Team::whereIn('ga_id', $request->geo_area)->orderBy('department_id', 'asc')->get();

        return view('reports.consumer.waiting-report.team-progress', [
            'teams' => $teams,
            'consumer_team_assign' => $consumer_team_assign,
        ]);
    }

    /**
     * Employee Progress List from the Team
     */
    public function employeeProgress(Request $request, $team_id)
    {
        // dd($request->all());
        // Get the Employee Progress Count for team
        $team = Team::find($team_id);
        $emp_ids = $team->users->pluck('id')->toArray();
        $team_employees = TeamConsumer::select('updated_by', DB::raw('COUNT(consumer_id) as completed_count'))->where('team_id', $team_id)->where('status', 1)->whereIn('updated_by', $emp_ids)->groupBy('updated_by')->get();
        $employees = [];
        foreach($team_employees as $emp) {
            $employees[$emp->updated_by] = array(
                'total' => $emp->completed_count ?? 0,
                'updated' => $emp->updatedBy->name ?? '',
            );
        }

        return view('reports.consumer.waiting-report.emp-progress-team', [
            'team' => $team,
            'employees' => $employees,
        ]);
    }
}