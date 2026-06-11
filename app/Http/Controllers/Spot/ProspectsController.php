<?php

namespace App\Http\Controllers\Spot;

use App\Enums\Role;
use App\Enums\SpotStages;
use App\Enums\SpotStatus;
use App\Exports\Spot\ProspectsExport;
use App\Http\Controllers\Controller;
use App\Models\Master\Cluster;
use App\Models\Master\FirmType;
use App\Models\Master\FuelType;
use App\Models\Master\Ga;
use App\Models\Master\IndustrialArea;
use App\Models\Master\Segment;
use App\Models\Admin\User;
use App\Models\Spot\ProspectApproval;
use App\Models\Spot\ProspectComments;
use App\Models\Spot\ProspectDateChangeRequest;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\ProspectPipeline;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Stage;
use App\Models\Spot\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProspectsController extends Controller
{
    /**
     * Display Prospect List with Filters, Sorting , Pagination and Export
     * 
     * This Method:
     * - Applies Dynamic Filters based on request
     * - Restricts Data based on user role
     * - supports dynamic sorting and pagination
     * - return partial view if request is ajax
     * 
     * @return view
     */
    public function index(Request $request)
    {
        // For sorting Data
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 10;
        $query = Prospects::with(['ga','segment','industrialArea','fuelType','stage', 'statusType'])->when($request->has('search_key'), function($q) use($request) {
            $q->where(function($q) use($request) {
                $q->where('name', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('code', 'like', '%'.$request->get('search_key').'%');
            });
        })
        // ->where('status_id', '!=', SpotStatus::CANCEL->value)
        ->When($request->has('geo_area'), function($q) use($request) {
            $q->whereIn('ga_id', $request->get('geo_area'));
        })->When($request->has('industrial_area_id'), function($q) use($request) {
            $q->whereIn('industrial_area_id', $request->get('industrial_area_id'));
        })->When($request->has('fuel_id'), function($q) use($request) {
            $q->whereIn('fuel_id', $request->get('fuel_id'));
        })->when($request->has('stage_id'), function($q) use($request) {
            $q->whereHas('stage', function($q2) use($request) {
                $q2->whereIn('parent_id', $request->get('stage_id'));
            });
        })->When($request->has('sub_stage_id'), function($q) use($request) {
            $q->whereIn('stage_id', $request->get('sub_stage_id'));
        })->When($request->has('status_id'), function($q) use($request) {
            $q->whereIn('status_id', $request->get('status_id'));
        })->When($request->has('segments'), function($q) use($request) {
            $q->whereIn('segment_id', $request->get('segments'));
        })
        ->when((!empty($request->expected_date_from) and !empty($request->expected_date_to)), function($q) use($request) {
            $q->whereBetween('expected_date', [Carbon::createFromFormat('d-m-Y', $request->expected_date_from)->toDateString(), Carbon::createFromFormat('d-m-Y', $request->expected_date_to)->toDateString()]);
        })
        ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        });
        if(! (isAdmin() OR isGaHead() OR isClusterHead() OR isFullAccess() OR isSuperAdmin())) {
            $query->whereIn('ga_id', session()->get('user')['gas']);
        }
        $prospects = $query->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        $stages = Stage::where('type', 1)->where('parent_id', NULL)->get();
        $status = Status::all();
        $potential = Prospects::with(['ga','segment','industrialArea','fuelType','stage', 'statusType'])
            ->select('stage_id', DB::raw('SUM(potential) as potential_val'))
            ->when($request->has('search_key'), function($q) use($request) {
                $q->where(function($q) use($request) {
                    $q->where('name', 'like', '%'.$request->get('search_key').'%');
                    $q->orWhere('code', 'like', '%'.$request->get('search_key').'%');
                });
            })
            ->when(!(isAdmin() OR isGaHead() OR isClusterHead() OR isFullAccess() OR isSuperAdmin()), function($q) {
                $q->whereIn('ga_id', session()->get('user')['gas']);
            })
            ->When($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->When($request->has('industrial_area_id'), function($q) use($request) {
                $q->whereIn('industrial_area_id', $request->get('industrial_area_id'));
            })->When($request->has('fuel_id'), function($q) use($request) {
                $q->whereIn('fuel_id', $request->get('fuel_id'));
            })->when($request->has('stage_id'), function($q) use($request) {
                $q->whereHas('stage', function($q2) use($request) {
                    $q2->whereIn('parent_id', $request->get('stage_id'));
                });
            })->When($request->has('sub_stage_id'), function($q) use($request) {
                $q->whereIn('stage_id', $request->get('sub_stage_id'));
            })
            ->when(!$request->has('sub_stage_id'), function ($q) {
                $q->whereNot('stage_id', SpotStages::LOSE->value);
            })
            ->When($request->has('status_id'), function($q) use($request) {
                $q->whereIn('status_id', $request->get('status_id'));
            })->When($request->has('segments'), function($q) use($request) {
                $q->whereIn('segment_id', $request->get('segments'));
            })
            ->when((!empty($request->expected_date_from) and !empty($request->expected_date_to)), function($q) use($request) {
                $q->whereBetween('expected_date', [Carbon::createFromFormat('d-m-Y', $request->expected_date_from)->toDateString(), Carbon::createFromFormat('d-m-Y', $request->expected_date_to)->toDateString()]);
            })
            ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
                $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
            })->groupBy('stage_id')->get()->pluck('potential_val', 'stage_id');
        if($request->ajax()) {
            return view('spot.prospects.list-body', ['prospects' => $prospects, 'stages' => $stages, 'status_list' => $status, 'potential' => $potential]);
        }
        return view('spot.prospects.list', ['prospects' => $prospects, 'stages' => $stages, 'status_list' => $status, 'potential' => $potential]);
    }

    /**
     * Display the create form.
     * Fetch the Required details to load the form.
     * @return view 
     */
    public function create()
    {
        $geo_areas = Ga::all();
        $clusters = Cluster::all();
        $firm_types = FirmType::all();
        $fuel_types = FuelType::all();
        $segments = Segment::all();
        return view('spot.prospects.create', [
            'geo_areas' => $geo_areas,
            'clusters' => $clusters,
            'firm_types' => $firm_types,
            'fuel_types' => $fuel_types,
            'segments' => $segments,
            'industrial_areas' => [],
            'users_list' => [],
        ]);
    }

    /**
     * Add/Inserts the new prospect.
     * 
     * This Method:
     * - Validates the required fields
     * - Default $stage_id = Research, $status_id = INProgress
     * - Loads the GA Details for state_id, cluster_id
     * - Adds the Prospect Master Data
     * - Tracks the user who added the prospect
     *
     * Tables Created in:
     * - ProspectstatusHistory (If Prospect record exists)
     * - ProspectApproval (If Prospect record exists)
     * 
     * Error Occured (If Prospect doesn't add)
     * 
     * @return response string
     */
    public function store(Request $request)
    {
        $request->validate([
            'ga_id' => 'required',
            'name' => 'required',
            'cluster_head' => 'required',
            'ga_head' => 'required',
            'sales_officer' => 'required',
            'segment_id' => 'required',
            'industrial_area_id' => 'required',
        ]);
        // $stage = 1;
        $status = SpotStatus::IN_PROGRESS->value;
        $stage_id = SpotStages::RESEARCH->value;

        $ga_val = Ga::find($request->ga_id); 
        // TO insert into the Prospect
        $add_prospect = Prospects::create([
            'name' => $request->name,
            'firm_id' => $request->firm_id,
            'fuel_id' => $request->fuel_id,
            'fuel_consumption' => $request->fuel_consumption,
            'unit_id' => $request->unit_id,
            'potential' => $request->potential,
            'expected_date' =>  !empty($request->expected_date) ? Carbon::createFromFormat('d-m-Y', $request->expected_date) : null,
            'zone' => $request->zone,
            'stage_id' => $stage_id,
            'status_id' => $status,
            'status_date' => Carbon::now(),
            'ga_id' => $request->ga_id,
            'state_id' => $ga_val['state_id'],
            'cluster_id' => $ga_val['cluster_id'],
            'industrial_area_id' => $request->industrial_area_id,
            'cluster_head' => $request->cluster_head,
            'ga_head' => $request->ga_head,
            'sales_officer' => $request->sales_officer,
            'segment_id' => $request->segment_id,
            'notes' => $request->notes,
            'pipeline_availability' => $request->pipeline_availability,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_by' => Auth::id(),
        ]);
        if($add_prospect->id) {
            // Prospect Code Update
            $prospect_code = 'SP' . date('ym') . str_pad($add_prospect->id, 4, '0', STR_PAD_LEFT);
            Prospects::where('id', $add_prospect->id)->update(['code' => $prospect_code]);
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id'        => $add_prospect->id,
                'stage_id' => $stage_id,
                'status_id' => $status,
                'notes'          => $request->notes,
                'created_at'       => Carbon::now(),
                'created_by'       => Auth::id(),
            ]);
            // Insert into Prospect Approval
            ProspectApproval::create([
                'prospect_id' => $add_prospect->id,
                'status_id' => SpotStages::APPROACH->value, //Approach
                'status' => 0,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);
            // Response Message
            return response()->json(['success' => 'Prospect Details Created Successfully']);
        }
        return response()->json(['success' => 'Error in Inserting Data']);
    }

    /**
     * Get Industrial Area Based on GA
     */
    public function getDetailsByGA(Request $request)
    {
        $users_list = User::select('id', 'first_name', 'last_name')->whereHas('ga', function($q) use($request) {
            $q->where('mst_gas.id', $request->ga_id);
        })->whereHas('roles', function($q) use($request) {
            $q->whereIn('role_id', [Role::CLUSTER_HEAD->value, Role::GA_HEAD->value, Role::SALES_OFFICER->value]);
        })->get();
        $industrial_areas = IndustrialArea::where('ga_id', $request->ga_id)->get();
        return view('spot.prospects.add-sub-form-list', [
            'industrial_areas' => $industrial_areas,
            'users_list' => $users_list,
        ]);
    }
    /**
     * Display Prospect Details Screen.
     *
     * This method:
     * - Loads the selected prospect details.
     * - Returns the full prospect detail view by default.
     * - If "reload" parameter is present, it dynamically loads specific partial sections based on the requested type.
     *
     * Reload Types:
     * 1 = Status History
     * 2 = Documents
     * 3 = Pipeline
     * 4 = Date Request
     * 5 = Comments
     * 6 = Status Hold
     * 7 = Status Cancel
     * 8 = GA Approval
     *
     */
    public function show(Request $request, string $id)
    {
        $prospect = Prospects::find($id);
        $reload = $request->has('reload') ? true : false;
        if($reload == true) {
            switch($request->type) {
                case 1:
                    return view('spot.prospects.status-history.list', [
                        'prospect' => $prospect,
                    ]);
                    break;
                case 2:
                    return view('spot.prospects.documents.list', [
                        'prospect' => $prospect,
                    ]);
                    break;
                case 3: 
                    return view('spot.prospects.pipeline.list', [
                        'prospect' => $prospect,
                    ]);
                    break;
                case 4:
                    return view('spot.prospects.date-request.list', [
                        'prospect' => $prospect,
                    ]);
                    break;
                case 5:
                    return view('spot.prospects.comments.comments',[
                        'prospect' => $prospect,
                     ]);
                    break;
                default:
                    echo "";    
            }
        }
        // Response
        return view('spot.prospects.show', [
            'prospect' => $prospect, 
            'type' => 0,
        ]);
    }

    /**
     * Display the Prospect Edit Form
     * - Loads the selected prospect details.
     * - Loads the required details for the Edit.
     * @return view
     */
    public function edit(string $id)
    {
        $prospect = Prospects::find($id);
        $geo_areas = Ga::all();
        $clusters = Cluster::all();
        $firm_types = FirmType::all();
        $fuel_types = FuelType::all();
        $segments = Segment::all();
        $users_list = User::select('id', 'first_name', 'last_name')->whereHas('ga', function($q) use($prospect) {
            $q->where('ga_id', $prospect->ga_id);
        })->whereHas('roles', function($q) {
            $q->whereIn('role_id', [Role::CLUSTER_HEAD->value,Role::GA_HEAD->value,Role::SALES_OFFICER->value]);
        })->get();
        $industrial_areas = IndustrialArea::where('ga_id', $prospect->ga_id)->get();
        return view('spot.prospects.edit', [
            'prospect' => $prospect,
            'geo_areas' => $geo_areas,
            'clusters' => $clusters,
            'firm_types' => $firm_types,
            'fuel_types' => $fuel_types,
            'industrial_areas' => $industrial_areas,
            'segments' => $segments,
            'users_list' => $users_list,
        ]);
    }

    /**
     * Get Industrial Area Based on GA
     */
    public function getEditDetailsByGA(Request $request)
    {
        $users_list = User::select('id', 'first_name', 'last_name')->whereHas('ga', function($q) use($request) {
            $q->where('ga_id', $request->ga_id);
        })->whereHas('roles', function($q) use($request) {
            $q->whereIn('role_id', [Role::CLUSTER_HEAD->value,Role::GA_HEAD->value,Role::SALES_OFFICER->value]);
        })->get();
        $industrial_areas = IndustrialArea::where('ga_id', $request->ga_id)->get();
        return view('spot.prospects.add-sub-form-list', [
            'industrial_areas' => $industrial_areas,
            'users_list' => $users_list,
        ]);
    }

    /**
     * Updates the Prospect Details
     * 
     * This Method:
     * 1.Validates the required fields
     * 2.Retrieve the GA Details
     * 3.Updates the Prospect Master Data
     * 4.Handles pipeline availablity logic(if 1 then it deletes pipeline records related to that prospect)
     * 5.Tracks the user who updated this prospect
     * @return response string
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'ga_id' => 'required',
            'name' => 'required',
            'cluster_head' => 'required',
            'ga_head' => 'required',
            'sales_officer' => 'required',
            'segment_id' => 'required',
            'industrial_area_id' => 'required',
        ]);
        $ga_val = Ga::find($request->ga_id); 
        // TO UPdate into the Prospects
        $update_prospect = Prospects::where('id', $id)->update([
            'name' => $request->name,
            'firm_id' => $request->firm_id,
            'fuel_id' => $request->fuel_id,
            'fuel_consumption' => $request->fuel_consumption,
            'unit_id' => $request->unit_id,
            'potential' => $request->potential,
            'expected_date' =>  !empty($request->expected_date) ? Carbon::createFromFormat('d-m-Y', $request->expected_date) : null,
            'zone' => $request->zone,
            'ga_id' => $request->ga_id,
            'state_id' => $ga_val['state_id'],
            'cluster_id' => $ga_val['cluster_id'],
            'industrial_area_id' => $request->industrial_area_id,
            'cluster_head' => $request->cluster_head,
            'ga_head' => $request->ga_head,
            'sales_officer' => $request->sales_officer,
            'segment_id' => $request->segment_id,
            'notes' => $request->notes,
            'pipeline_availability' => $request->pipeline_availability,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'updated_by' => Auth::id(),
        ]);
        // Pipeline Availability
        if ($request->pipeline_availability == 1) {
            // Delete if any record exists
           $delete_prospect =  ProspectPipeline::where('prospect_id', $id)->delete();
        }
        // Response Message
        return response()->json(['success' => 'Prospect Details Updated Successfully']);        
    }

    /**
     * To export the Prospects
     */
    public function prospectsExport(Request $request)
    {
        return (new ProspectsExport($request))->download('prospects_report'.now()->format('YmdHis').'.csv');
    }

    /**
     * To Delete the Prospect and related records
     */
    public function destroy($id)
    {
        // Delete Child Tables
        ProspectApproval::where('prospect_id', $id)->delete();
        ProspectComments::where('prospect_id', $id)->delete();
        ProspectPipeline::where('prospect_id', $id)->delete();
        ProspectStatusHistory::where('prospect_id', $id)->delete();
        ProspectDateChangeRequest::where('prospect_id', $id)->delete();
        ProspectDocuments::where('prospect_id', $id)->delete();
        // Delete Parent Table
        Prospects::destroy($id);
        Session::flash('success', 'Prospect Deleted Successfully');
    }
}
