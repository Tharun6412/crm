<?php

namespace App\Http\Controllers\Consumer;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\TeamConsumer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamConsumersController extends Controller
{
    public function index(Request $request)
    {
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
        // Get Consumers List
        $consumers = Consumer::with(['ga', 'ca', 'area','subArea', 'status','teamConsumer.team'])->leftJoin('cns_consumer_teams', function ($join) use($request) {
            $join->on('cns_consumers.id', '=', 'cns_consumer_teams.consumer_id')
                ->whereIn('cns_consumer_teams.status_id', $request->target_status);
            })
            ->leftJoin('adm_teams', 'adm_teams.id', '=', 'cns_consumer_teams.team_id')
            ->leftJoin('users', 'users.id', '=', 'cns_consumer_teams.created_by')
            ->leftJoin('mst_cns_status', 'mst_cns_status.id', '=', 'cns_consumer_teams.status_id')
            ->select(
                'cns_consumers.id','cns_consumers.fname','cns_consumers.lname','cns_consumers.crn','cns_consumers.t_crn','cns_consumers.ga_id','cns_consumers.ca_id','cns_consumers.area_id','cns_consumers.subarea_id','cns_consumers.status_id',
                'adm_teams.name as team_name',
                'cns_consumer_teams.status as team_status',
                'mst_cns_status.name as status_name',
                'users.first_name as team_created_by',
                'cns_consumer_teams.status_id as team_status_id'
            )
            ->when($request->filled('key'), function($q) use ($request) {
                $q->where('cns_consumers.t_crn','like','%'.$request->key.'%')->orWhere('cns_consumers.crn','like','%'.$request->key.'%');
            })
            ->when($request->has('team_id'), function ($q) use($request) {
                $q->whereIn('team_id', $request->team_id);
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
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('cns_consumers.ga_id', $request->geo_area);
            })
            ->when($request->has('charge_area'), function($q) use ($request) {
                $q->whereIn('cns_consumers.ca_id', $request->charge_area);
            })
            ->paginate(50)->withQueryString();
        // Get Teams List
        $teams = Team::whereIn('ga_id',$request->ugas)->whereHas('cas', function($q) use($request) {
            $q->whereIn('mst_cas.id', $request->ucas);
        })->where('department_id', $dept_id)->where('status',1)->get();
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
        $teams = Team::where('ga_id',$team_consumer->ga_id)->whereHas('cas', function($q) use($team_consumer) {
            $q->where('mst_cas.id', $team_consumer->ca_id);
        })->where('department_id', $dept_id)->where('status',1)->get();
        return view('consumers.team-consumers.create',['team_consumer' => $team_consumer,'teams' => $teams]);
    }
    public function store(Request $request, $id)
    {
        $request->validate([
            'team_id' => 'required',
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
        dd($request->all());
        return response()->json('Consumers assigned successfully');
    }
}