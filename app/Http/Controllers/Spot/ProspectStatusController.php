<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProspectStatusController extends Controller
{
    /**
     * Status Update
     */
    public function editStatus(Request $request, $id)
    {
        $status_list = Status::where('type', 1)->where('parent_id', NULL)->get();
        $prospect = Prospects::find($id);
        $sub_stages = Status::where('parent_id', $prospect->stage->parent->id)->get();
        if($request->type == "1") {
            return view('spot.prospects.status-history.edit', [
                'status_list' => $status_list,
                'type' => 1,
                'id' => $id,
                'prospect' => $prospect,
                'sub_stages' => $sub_stages,
            ]);    
        }
        return view('spot.prospects.show', [
            'status_list' => $status_list,
            'type' => 1,
            'id' => $id,
            'prospect' => $prospect,
            'sub_stages' => $sub_stages,
        ]);
    }

    /**
     * Get Sub Stages by Status ID
     */
    public function getSubStagesByStage(Request $request)
    {
        $sub_stages = Status::where('parent_id', $request->stage_id)->get();
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
            // 'stage' => $request->stage_id,
            'stage_id' => $request->sub_stage_id,
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
                'stage_id' => $request->sub_stage_id,
                'notes'          => $request->notes,
                'created_at'       => Carbon::now(),
                'created_by'       => Auth::id(),
            ]);

            // Update status based on stage
            switch($request->sub_stage_id) {
                case '22' : // Closure -> Win
                    ProspectDocuments::where('id', $request->offer_document)->update(['win' => 1]);
                    $update_status = array('status_id' => 10);
                    break;
                case '23' : // Lose
                    $update_status = array('status_id' => 26);
                    break;
                case '18' : //Offer
                    $update_status = array('status_id' => 8);
                    break;
                case '24' : // Order -> Execution
                case '25' : // Order -> Commission
                    $update_status = array('status_id' => 10);
                    break;
                default :
                    $update_status = array('status_id' => 7);
            }
            // Status Update Query for Prospects 
            if($update_status['status_id'] > 0) {
                Prospects::where('id', $id)->update($update_status);
            }
            return response()->json(['success' => 'Status updated Successfully']);
        }
    }
    /**
     * To Hold
     */
    public function hold(Request $request, $id)
    {
        $prospect = Prospects::find($id);
        if($request->type == "6") {
            return view('spot.prospects.status-history.hold', [
                'type' => 6,
                'id' => $id,
                'prospect' => $prospect,
            ]);    
        }
        return view('spot.prospects.show', [
            'type' => 6,
            'id' => $id,
            'prospect' => $prospect,
        ]);
    }

    /**
     * To Hold the status
     */
    public function updateHoldStatus(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Update the status in Prospects
        Prospects::where('id', $id)->update([
            'status_id' => 11,
        ]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => 11,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status updated Successfully']);
    }

    /**
     * To Cancel the status
     */
    public function cancel(Request $request, $id)
    {
        $prospect = Prospects::find($id);
        if($request->type == "7") {
            return view('spot.prospects.status-history.cancel', [
                'type' => 7,
                'id' => $id,
                'prospect' => $prospect
            ]);    
        }
        return view('spot.prospects.show', [
            'type' => 7,
            'id' => $id,
            'prospect' => $prospect
        ]);
    }
    /**
     * To Cancel the status
     */
    public function updateCancelStatus(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Update the status in Prospects
        Prospects::where('id', $id)->update([
            'status_id' => 12,
        ]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => 12,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status updated Successfully']);
    }
}