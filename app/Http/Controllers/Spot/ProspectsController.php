<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Admin\Cluster;
use App\Models\Admin\FirmTypes;
use App\Models\Admin\FuelTypes;
use App\Models\Admin\Ga;
use App\Models\Admin\IndustrialAreas;
use App\Models\Spot\DocumentTypes;
use App\Models\Spot\ProspectApproval;
use App\Models\Spot\ProspectComments;
use App\Models\Spot\ProspectDateChangeRequest;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\ProspectPipeline;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Status;
use Carbon\Carbon;
use Dom\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        $query = Prospects::when($request->has('search_key'), function($q) use($request) {
            $q->where(function($q) use($request) {
                $q->where('name', 'like', '%'.$request->get('search_key').'%');
                $q->orWhere('code', 'like', '%'.$request->get('search_key').'%');
            });
        });
        $prospects = $query->orderBy($sortBy, $sortOr)->paginate(2)->withQueryString();
        if($request->ajax()) {
            return view('spot.prospects.list-body', ['prospects' => $prospects]);
        }
        return view('spot.prospects.list', ['prospects' => $prospects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $geo_areas = Ga::all();
        $clusters = Cluster::all();
        $firm_types = FirmTypes::all();
        $fuel_types = FuelTypes::all();
        return view('spot.prospects.create', [
            'geo_areas' => $geo_areas,
            'clusters' => $clusters,
            'firm_types' => $firm_types,
            'fuel_types' => $fuel_types,
            'industrial_areas' => [],
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
            'segment_id' => 'required',
            'firm_id' => 'required',
            'fuel_id' => 'required',
        ]);
        $stage = 1;
        $status = 7;
        $sub_stage_id =13;

        $ga_val = Ga::find($request->ga_id); 
        // TO insert into the Vehicle
        $add_prospect = Prospects::create([
            'name' => $request->name,
            'firm_id' => $request->firm_id,
            'fuel_id' => $request->fuel_id,
            'fuel_consumption' => $request->fuel_consumption,
            'unit_id' => $request->unit_id,
            'potential' => $request->potential,
            'expected_date' =>  Carbon::createFromFormat('d-m-Y', $request->expected_date) ?? null,
            'zone' => $request->zone,
            'status' => $status,
            'stage' => $stage,
            'sub_stage_id' => $sub_stage_id,
            'status_date' => Carbon::now(),
            'ga_id' => $request->ga_id,
            'state_id' => $ga_val['state_id'],
            'cluster_id' => $ga_val['cluster_id'],
            'industrial_area_id' => $request->industrial_area_id,
            // 'ga_head'          => $this->input->post('ga_head'),
            // 'cluster_head'     => $this->input->post('cluster_head'),
            // 'sales_officer' => $this->input->post('sales_officer'),
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
            // Pipeline Availability
            if ($request->pipeline_availability == 2) {
                $pipe_ar = [];
                if ($request->steel_pipeline > 0) {
                    $pipe_ar[] = array(
                        'prospect_id' => $add_prospect->id,
                        'pipe_type' => 1,
                        'length' => $request->steel_pipeline,
                        'status' => 0,
                        'created_by' => Auth::id(), 
                    );
                }
                if ($request->mdpe_pipeline > 0) {
                    $pipe_ar[] = array(
                        'prospect_id' => $add_prospect->id,
                        'pipe_type' => 2,
                        'length' => $request->mdpe_pipeline,
                        'status' => 0,
                        'created_by' => Auth::id(), 
                    );
                }
                if(!empty($pipe_ar)) {
                    ProspectPipeline::upsert($pipe_ar, ['prospect_id', 'pipe_type'], ['length', 'status', 'created_by']);
                }
            }
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id'        => $add_prospect->id,
                'status_id' => $stage,
                'sub_status_id' => $sub_stage_id,
                'notes'          => $request->notes,
                'created_at'       => Carbon::now(),
                'created_by'       => Auth::id(),
            ]);
            // Insert into Prospect Approval
            ProspectApproval::create([
                'prospect_id' => $add_prospect->id,
                'status_id' => 3,
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
    public function getIndustrialAreaByGA(Request $request)
    {
        $industrial_areas = IndustrialAreas::where('ga_id', $request->ga_id)->get();
        return response()->json(['industrial_areas' => $industrial_areas]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        // print "<pre>"; print_r($request->all());
        // print "<pre>"; print_r($id); exit;
        $prospect = Prospects::find($id);
        $prospect_documents = ProspectDocuments::where('prospect_id', $id)->get();
        $prospect_status_history = ProspectStatusHistory::where('prospect_id', $id)->get();
        $prospect_date_change_history = ProspectDateChangeRequest::where('prospect_id', $id)->orderByDesc('id')->get();
        $prospect_pipeline = ProspectPipeline::where('prospect_id', $id)->orderByDesc('id')->get();
        $prospect_comments = ProspectComments::where('prospect_id', $id)->orderByDesc('id')->get();
        $reload = $request->has('reload') ? true : false;
        if($reload == true) {
            switch($request->type) {
                case 1:
                    return view('spot.prospects.status-history.list', [
                        'prospect' => $prospect,
                        'prospect_status_history' => $prospect_status_history,
                        'prospect_documents' => $prospect_documents,
                    ]);
                    break;
                case 5:
                    return view('spot.prospects.comments',[
                        'prospect' => $prospect,
                        'prospect_comments' => $prospect_comments,
                     ]);
                    break;
                case 6: 
                    return view('spot.prospects.pipeline.list', [
                        'prospect' => $prospect,
                        'prospect_pipeline' => $prospect_pipeline,
                    ]);
                    break;
                case 7:
                    return view('spot.prospects.date-request.list', [
                        'prospect' => $prospect,
                        'prospect_date_change_history' => $prospect_date_change_history,
                    ]);
                    break;
                case 8:
                    return view('spot.prospects.documents.list', [
                        'prospect' => $prospect,
                        'prospect_documents' => $prospect_documents
                    ]);
                    break;
                default:
                    echo "";    
            }
        }
        return view('spot.prospects.show', [
            'prospect' => $prospect, 
            'type' => 0,
            'prospect_documents' => $prospect_documents,
            'prospect_status_history' => $prospect_status_history,
            'prospect_date_change_history' => $prospect_date_change_history,
            'prospect_pipeline' => $prospect_pipeline,
            'prospect_comments' => $prospect_comments,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prospect = Prospects::find($id);
        // PipeLine Availability
        $steel_pipe = ProspectPipeline::where('prospect_id', $id)->where('pipe_type', 1)->first();
        $mdpe_pipe = ProspectPipeline::where('prospect_id', $id)->where('pipe_type', 2)->first();
        $geo_areas = Ga::all();
        $clusters = Cluster::all();
        $firm_types = FirmTypes::all();
        $fuel_types = FuelTypes::all();
        $industrial_areas = IndustrialAreas::where('ga_id', $prospect->ga_id)->get();
        return view('spot.prospects.edit', [
            'prospect' => $prospect,
            'geo_areas' => $geo_areas,
            'clusters' => $clusters,
            'firm_types' => $firm_types,
            'fuel_types' => $fuel_types,
            'industrial_areas' => $industrial_areas,
            'steel_pipe' => $steel_pipe,
            'mdpe_pipe' => $mdpe_pipe,
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
            'segment_id' => 'required',
            'firm_id' => 'required',
            'fuel_id' => 'required',
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
            'expected_date' =>  Carbon::createFromFormat('d-m-Y', $request->expected_date) ?? null,
            'zone' => $request->zone,
            'ga_id' => $request->ga_id,
            'state_id' => $ga_val['state_id'],
            'cluster_id' => $ga_val['cluster_id'],
            'industrial_area_id' => $request->industrial_area_id,
            'segment_id' => $request->segment_id,
            'notes' => $request->notes,
            'pipeline_availability' => $request->pipeline_availability,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        // Pipeline Availability
        if ($request->pipeline_availability == 2) {
            $pipe_ar = [];
            if ($request->steel_pipeline > 0) {
                $pipe_ar[] = array(
                    'prospect_id' => $id,
                    'pipe_type' => 1,
                    'length' => $request->steel_pipeline,
                    'status' => 0,
                    'created_by' => Auth::id(), 
                );
            }
            if ($request->mdpe_pipeline > 0) {
                $pipe_ar[] = array(
                    'prospect_id' => $id,
                    'pipe_type' => 2,
                    'length' => $request->mdpe_pipeline,
                    'status' => 0,
                    'created_by' => Auth::id(), 
                );
            }
            if(!empty($pipe_ar)) {
                ProspectPipeline::upsert($pipe_ar, ['prospect_id', 'pipe_type', 'status'], ['length', 'status', 'created_by']);
            }
        }else {
            // Delete if any record exists
           $delete_prospect =  ProspectPipeline::where('prospect_id', $id)->delete();
        }
        // Response Message
        return response()->json(['success' => 'Prospect Details Updated Successfully']);        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Status Update
     */
    public function editStatus(Request $request, $id)
    {
        $status_list = Status::where('type', 1)->where('parent', 0)->get();
        $prospect = Prospects::find($id);
        $prospect_documents = ProspectDocuments::where('prospect_id', $id)->get();
        $prospect_status_history = ProspectStatusHistory::where('prospect_id', $id)->get();
        $prospect_date_change_history = ProspectDateChangeRequest::where('prospect_id',$id)->orderByDesc('id')->get();
        $prospect_pipeline = ProspectPipeline::where('prospect_id', $id)->orderByDesc('id')->get();
        $prospect_comments = ProspectComments::where('prospect_id', $id)->orderByDesc('id')->get();
        $sub_stages = Status::where('parent', $prospect->stage)->get();
        if($request->type == "1") {
            return view('spot.prospects.status-history.edit', [
                'status_list' => $status_list,
                'type' => 1,
                'id' => $id,
                'prospect' => $prospect,
                'prospect_documents' => $prospect_documents,
                'sub_stages' => $sub_stages,
                'prospect_status_history' => $prospect_status_history,
                'prospect_date_change_history' => $prospect_date_change_history,
                'prospect_pipeline' => $prospect_pipeline,
                'prospect_comments' => $prospect_comments,
            ]);    
        }
        return view('spot.prospects.show', [
            'status_list' => $status_list,
            'type' => 1,
            'id' => $id,
            'prospect' => $prospect,
            'prospect_documents' => $prospect_documents,
            'sub_stages' => $sub_stages,
            'prospect_status_history' => $prospect_status_history,
            'prospect_date_change_history' => $prospect_date_change_history,
            'prospect_pipeline' => $prospect_pipeline,
            'prospect_comments' => $prospect_comments,
        ]);
    }

    /**
     * Get Sub Stages by Status ID
     */
    public function getSubStagesByStage(Request $request)
    {
        $sub_stages = Status::where('parent', $request->stage_id)->get();
        return response()->json(['sub_stages' => $sub_stages]);
    }

    /**
     * Get Status Info by Sub Stage ID
     */
    public function getDetailsBySubStage(Request $request)
    {
        $sub_stage_id = $request->sub_stage_id;
        $prospect_id = $request->prospect_id;
        $prospect = Prospects::find($prospect_id);
        if($sub_stage_id == 22) {
            $offer_type_docs = ProspectDocuments::where(['document_type_id' => 2, 'prospect_id' => $prospect_id, 'status' => 1])->get();
        }
        return view('spot.prospects.status-history.sub_stage_details', [
            'sub_stage_id' => $sub_stage_id,
            'prospect' => $prospect,
            'prospect_id' => $prospect_id,
            'offer_type_docs' => $offer_type_docs ?? [],
        ]);
    }

    /**
     * To update Status
     */
    public function updateStatus(Request $request , $id)
    {
        $rules = [
            'stage_id' => 'required',
            'sub_stage_id' => 'required',
            'notes' => 'required',
        ];
        switch($request->sub_stage_id) {
            case 17 :
                $rules['expected_date'] = 'required';
                break;
            case 22 :
                $rules['offer_document'] = 'required';
                break;
            default:
                echo "";
        }
        $request->validate($rules);
        switch($request->sub_stage_id) {
            case 17:
            case 18:
                $doc_type = $request->sub_stage_id == "17" ? 1 : 2;
                $document_upload = DocumentUpload::upload($request, 'spot');
                $doc_offer_count = ProspectDocuments::where('prospect_id', $id)->where('document_type_id', $doc_type)->count();
                // To insert into the Prospect Documents
                ProspectDocuments::create([
                    'prospect_id' => $id,
                    'document_type_id' => $doc_type,
                    'offer_count' => $doc_offer_count+1,
                    'doc_file_id' => $document_upload['file_id'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(), 
                ]);
                break;
            default: //none
        }
        // Update Prospect Array Details
        $update_status_list = array(
            'stage' => $request->stage_id,
            'sub_stage_id' => $request->sub_stage_id,
            'status_date' => Carbon::now(),
        );
        if($request->sub_stage_id == 17)
        {
            $update_status_list['expected_date'] = Carbon::createFromFormat('d-m-Y', $request->expected_date);
        }
        // Update to Prospects Table
        $statusUpdate = Prospects::where('id', $id)->update($update_status_list);
        if($statusUpdate) {
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id'        => $id,
                'status_id' => $request->stage_id,
                'sub_status_id' => $request->sub_stage_id,
                'notes'          => $request->notes,
                'created_at'       => Carbon::now(),
                'created_by'       => Auth::id(),
            ]);

            // Update status based on stage
            switch($request->sub_stage_id) {
                case '22' : // Closure -> Win
                    ProspectDocuments::where('id', $request->offer_document)->update(['win' => 1]);
                    $update_status = array('status' => 10);
                    break;
                case '23' : // Lose
                    $update_status = array('status' => 26);
                    break;
                case '18' : //Offer
                    $update_status = array('status' => 8);
                    break;
                case '24' : // Order -> Execution
                case '25' : // Order -> Commission
                    $update_status = array('status' => 10);
                    break;
                default :
                    $update_status = array('status' => 7);
            }
            // Status Update Query for Prospects 
            if($update_status['status'] > 0) {
                Prospects::where('id', $id)->update($update_status);
            }
            return response()->json(['success' => 'Status updated Successfully']);
        }
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
}
