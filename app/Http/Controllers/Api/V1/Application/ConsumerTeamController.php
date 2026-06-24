<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\ConsumerStatus;
use App\Enums\Department;
use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\TeamConsumer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumerTeamController extends Controller
{
    use ApiResponse;
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        if(empty($request->cns_status) OR empty($request->target_status) OR empty($request->ugas) OR empty($request->ucas)) {
            return response()->json(['message' => 'Consumer not found or invalid details provided.'], 422);
        }
        // Getting the UnAssigned List
        $consumers = Consumer::with([
                'ga:id,name', 
                'ca:id,name', 
                'area:id,name',
                'subArea:id,name', 
                'status:id,name',
                'teamConsumer.team:id,name'
            ])->leftJoin('cns_consumer_teams', function ($join) use($request) {
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
            ->paginate(50);
        $consumers_wait = $this->apiPagination($consumers);
        return response()->json([
            'consumers_waiting' => $consumers_wait,
        ], 200);
    }

    /**
     * Assign to a team
     */
    public function assignTeam(Request $request, $id)
    {
        $team_consumer = Consumer::select('id', 'status_id', 'ga_id', 'ca_id')->findOrFail($id)->makeHidden(['name']);
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
        $teams = Team::select('id', 'name')->where('ga_id',$team_consumer->ga_id)->whereHas('cas', function($q) use($team_consumer) {
            $q->where('mst_cas.id', $team_consumer->ca_id);
        })->where('department_id', $dept_id)->where('status', 1)->get();
        // Response
        return response()->json(['consumer' => $team_consumer, 'teams' => $teams], 200);
    }

    /**
     * Update Team Assignment to a Consumer
     */
    public function updateTeamAssignment(Request $request, $id)
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
            // Resposne
            return response()->json(['success' => 'Team Successfully assigned to Consumer'], 200);
        }else{
            // Response
            return response()->json(['success' => 'Error in Updating'], 200);
        }
    }
}