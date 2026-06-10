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
        $consumers = Consumer::with(['teamConsumer.team'])
        ->select('id','fname','lname','crn','status_id')
        ->when($request->filled('key'), function($q) use ($request) {
            $q->where('crn','like','%'.$request->key.'%');
        })
        ->when($request->has('geo_area'), function ($q) use($request) {
            $q->whereIn('ga_id', $request->geo_area);
        })
        ->when($request->has('district'), function ($q) use ($request) {
            $q->whereIn('district_id', $request->district);
        })
        ->when($request->has('team_id'), function ($q) use($request) {
            $q->whereHas('teamConsumer', function ($q1) use ($request) {
                $q1->whereIn('team_id', $request->team_id);
            });
        })
        ->when($request->has('charge_area'), function($q) use ($request) {
            $q->whereIn('ca_id', $request->charge_area);
        })
        ->when(!empty($request->date_from) AND !empty($request->date_to), function($q) use ($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->when($request->has('cns_status'), function ($q) use($request) {
            $q->whereIn('status_id', $request->cns_status);
        })
        ->when($request->has('status'), function($q) use ($request) {
            if (in_array(2, $request->status)) {
                // Unassigned = no teamConsumer record exists
                $q->whereDoesntHave('teamConsumer');
            } else {
                $q->whereHas('teamConsumer', function ($q1) use ($request) {
                    $q1->whereIn('status', $request->status);
                });
            }
        })->paginate(50)->withQueryString();
        if($request->ajax())
            return view('consumers.team-consumers.list-body', ['consumers' => $consumers]);
        else
            return view('consumers.team-consumers.list', ['consumers' => $consumers]);
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
                $dept_id = Department::MDPE->value;
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
        $teams = Team::where('ga_id',$team_consumer->ga_id)->where('department_id', $dept_id)->where('status',1)->get();
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
                'updated_by' => Auth::id(),
            ]);
            return response()->json(['success' => 'Team Successfully assigned to Consumer']);
        }else{
            return response()->json(['success' => 'Error in Updating']);
        }    
    }
}