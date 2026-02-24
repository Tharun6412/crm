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
use Illuminate\Support\Facades\Session;
require_once app_path('Helpers\spotauth.php');

class ProspectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // For sorting Data
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 10;
        $query = Prospects::with(['stage'])->when($request->has('search_key'), function($q) use($request) {
            $q->where(function($q) use($request) {
                $q->where('name', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('code', 'like', '%'.$request->get('search_key').'%');
            });
        })
        // ->where('status_id', '!=', 12)
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
        })->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
            $q->whereBetween('expected_date', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        });
        if(! (isSpotAdmin() OR isSpotGaHead() OR isSpotClusterHead())) {
            $query->whereIn('ga_id', session()->get('user')['gas']);
        }
        $prospects = $query->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        $stages = Stage::where('type', 1)->where('parent_id', NULL)->get();
        if($request->ajax()) {
            return view('spot.prospects.list-body', ['prospects' => $prospects, 'stages' => $stages]);
        }
        return view('spot.prospects.list', ['prospects' => $prospects, 'stages' => $stages]);
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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
        // TO insert into the Vehicle
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
            $q->where('ga_id', $request->ga_id);
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
     * Display the specified resource.
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
        return view('spot.prospects.show', [
            'prospect' => $prospect, 
            'type' => 0,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
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
     * TO Update Pipeline
     */
    public function updatePipeLine(Request $request)
    {
        $update_pipeline = ProspectPipeline::find($request->id);
        if($update_pipeline) {
            $update_pipeline->update([
                'status' => 1,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
        }
        Session::flash('success', 'Pipeline updated successfully');
        return response()->json(['success' => 'Pipeline Updated Successfully']);
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
