<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Exports\Reports\Consumers\ConsumerAssignExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\TeamConsumer;
use App\Models\Lms\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeamConsumersController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'cns_consumers.created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
         // Mapping Departments based on Status
        $status_val = $request->cns_status[0] ?? '';
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
        // dd($request->all());
        if(empty($request->cns_status) OR empty($request->target_status) OR empty($request->ugas) OR empty($request->ucas)) {
            return redirect(url('myActivity'));
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
        $consumers = Consumer::with(['ga', 'ca', 'area','subArea', 'status','teamConsumer.team'])->leftJoin('cns_consumer_teams', function ($join) use($request) {
            $join->on('cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
                ->whereIn('cns_consumer_teams.status_id', $request->target_status);
            })
            ->leftJoinSub($latestStatus, 'latest_status', function($join) use($request) {
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
            ->when($request->filled('key'), function($q) use ($request) {
                $q->where('cns_consumers.t_crn','like','%'.$request->key.'%')->orWhere('cns_consumers.crn','like','%'.$request->key.'%');
            })
            ->when(!empty($request->team_id), function ($q) use($request) {
                $q->whereIn('team_id', $request->team_id);
            })
            ->when($request->has('user_id'), function ($q) use($request) {
                $q->where('cns_consumer_teams.created_by', $request->user_id);
            })
            ->when($request->has('status'), function ($q) use ($request) {
                if (in_array(2, $request->status)) {
                    // Unassigned
                    $q->whereNull('cns_consumer_teams.id')
                        ->when($request->has('cns_status'), function ($q1) use($request) {
                                $q1->whereIn('cns_consumers.status_id', $request->cns_status);
                        });
                } else {
                    // Assigned / Completed
                    $q->whereNotNull('cns_consumer_teams.id')->whereIn('cns_consumer_teams.status', $request->status)
                        ->when($request->has('target_status'), function ($q1) use($request) {
                                $q1->whereIn('cns_consumer_teams.status_id', $request->target_status);
                        });
                }
            })
            ->when($request->has('ugas'), function ($q) use($request) {
                $q->whereIn('cns_consumers.ga_id', $request->ugas);
            })
            ->when($request->has('ucas'), function($q) use ($request) {
                $q->whereIn('cns_consumers.ca_id', $request->ucas);
            })
            ->when($request->has('area_ids'), function($q) use ($request) {
                $q->whereIn('cns_consumers.area_id', $request->area_ids);
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('cns_consumers.ga_id', $request->geo_area);
            })
            ->when($request->has('charge_area'), function($q) use ($request) {
                $q->whereIn('cns_consumers.ca_id', $request->charge_area);
            })
            ->when($request->has('area'), function($q) use ($request) {
                $q->whereIn('cns_consumers.area_id', $request->area);
            })
            ->when($request->has('subarea'), function($q) use ($request) {
                $q->whereIn('cns_consumers.subarea_id', $request->subarea);
            })
            ->orderBy($sortBy, $sortOr)
            ->paginate($records)->withQueryString();
        // Get Teams List
        $teams = [];
        if($request->area) {
            $teams = Team::where('du_id', $request->du_id)->whereIn('ga_id', $request->ugas)->whereHas('areas', function($q) use($request) {
                $q->whereIn('mst_areas.id', $request->area);
            })->where('status', 1)->get();
        }
        if($request->ajax())
            return view('consumers.team-consumers.list-body', ['consumers' => $consumers, 'teams' => $teams]);
        else
            return view('consumers.team-consumers.list', ['consumers' => $consumers, 'teams' => $teams]);
    }
    /**
     * To add teams to consumers
     */
    public function create(Request $request, $id)
    {
        // dd($request->all());
        $team_consumer = Consumer::findOrFail($id);
        switch($team_consumer->status_id){
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
                $dept_id = Null;
                break;
        }
        $teams = Team::where('du_id', $request->du_id)->whereIn('ga_id', $request->ugas)->whereHas('areas', function($q) use($team_consumer) {
            $q->where('mst_areas.id', $team_consumer->area_id);
        })->where('status', 1)->get();
        return view('consumers.team-consumers.create',['team_consumer' => $team_consumer,'teams' => $teams]);
    }

    /**
     * Get Employees By Team.
     */
    public function getEmployeesByTeam(Request $request)
    {
        $team = Team::with(['users'])->find($request->team_id);
        // dd($users->users);
        return response()->json(['users' => $team->users]);
    }

    /**
     * To Assign the Consumer to a Team
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'team_id' => 'required',
            'assign_id' => 'required',
        ]);
        $team_consumer = Consumer::find($id);
        switch($team_consumer->status_id){
            case ConsumerStatus::PRE_REGISTER->value:
                $statusId = ConsumerStatus::REGISTER->value;
                break;
            case ConsumerStatus::REGISTER->value:
                $statusId = ConsumerStatus::ACCEPT->value;
                break; 
            case ConsumerStatus::ACCEPT->value:
                $statusId = ConsumerStatus::EXECUTE->value;
                break;
            case ConsumerStatus::EXECUTE->value:
                $statusId = ConsumerStatus::HSC->value;
                break;
            case ConsumerStatus::HSC->value:
                $statusId = ConsumerStatus::ACTIVATE->value;
                break;
            default :
                $statusId = Null;
                break;
        }
        if($statusId >0){
            TeamConsumer::create([
                'consumer_id' => $id,
                'status_id' => $statusId,
                'team_id' => $request->team_id,
                'status' => 0, //0 = inprogress,1 = completed
                'assign_to' => $request->assign_id,
                'created_by' => Auth::id(),
            ]);
            return response()->json(['success' => 'Team Successfully assigned to Consumer']);
        }else{
            return response()->json(['success' => 'Error in Updating']);
        }    
    }

    /**
     * Bulk Consumers Assign
     */
    public function consumersBatchAssign(Request $request)
    {
        $request->validate([
            'team_id' => 'required',
            'assign_to' => 'required',
        ]);
        $consumer_data = [];
        $team = Team::find($request->team_id);
        $inserted = 0;
        switch($request->status){
            case ConsumerStatus::PRE_REGISTER->value:
                $statusId = ConsumerStatus::REGISTER->value;
                break;
            case ConsumerStatus::REGISTER->value:
                $statusId = ConsumerStatus::ACCEPT->value;
                break; 
            case ConsumerStatus::ACCEPT->value:
                $statusId = ConsumerStatus::EXECUTE->value;
                break;
            case ConsumerStatus::EXECUTE->value:
                $statusId = ConsumerStatus::HSC->value;
                break;
            case ConsumerStatus::HSC->value:
                $statusId = ConsumerStatus::ACTIVATE->value;
                break;
            default :
                $statusId = Null;
                break;
        }
        foreach($request->consumer_ids as $key => $consumer_id) {
            $consumer = Consumer::find($consumer_id);
            if(in_array($consumer->area_id, $team->areas->pluck('id')->toArray())) {
                $consumer_data[] = array(
                    'consumer_id' => $consumer_id,
                    'status_id' => $statusId,
                    'team_id' => $request->team_id,
                    'assign_to' => $request->assign_to,
                    'status' => 0,
                    'created_by' => Auth::id(),
                );
                $inserted++;
            }
        }
        TeamConsumer::upsert($consumer_data, ['consumer_id', 'status_id', 'team_id'], [
            'consumer_id',
            'status_id',
            'team_id',
            'assign_to',
            'status',
            'created_by',
        ]);
        // dd($inserted);
        return response()->json([
            'total' => count($request->consumer_ids),
            'inserted' => $inserted,
            'message' => 'Bulk assignment completed successfully.',
        ]);
    }

    /**
     * Assigned Consumers Export 
     */
    public function exportAssignedConsumers(Request $request)
    {
        return (new ConsumerAssignExport($request))->download('consumers_assigned.csv');
    }
}