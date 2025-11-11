<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Spot\ProspectApproval;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
require_once app_path('Helpers/spotauth.php');

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
        if($prospect->stage_id == 16) {
            $offer_type_docs = ProspectDocuments::where(['document_type_id' => 2, 'prospect_id' => $id, 'status' => 1])->get();
        }
        if($request->type == "1") {
            return view('spot.prospects.status-history.edit', [
                'sub_stage_id' => $prospect->stage_id,
                'status_list' => $status_list,
                'type' => 1,
                'id' => $id,
                'prospect' => $prospect,
                'sub_stages' => $sub_stages,
                'offer_type_docs' => $offer_type_docs ?? [],
            ]);    
        }
        return view('spot.prospects.show', [
            'sub_stage_id' => $prospect->stage_id,
            'status_list' => $status_list,
            'type' => 1,
            'id' => $id,
            'prospect' => $prospect,
            'sub_stages' => $sub_stages,
            'offer_type_docs' => $offer_type_docs ?? [],
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
        if($sub_stage_id == 16) {
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
            case 11 :
                $rules['expected_date'] = 'required';
                break;
            case 16 :
                $rules['offer-document'] = 'required';
                break;
            default:
                echo "";
        }
        $request->validate($rules);
        switch($request->sub_stage_id) {
            case 11:
            case 12:
                $doc_type = $request->sub_stage_id == "11" ? 1 : 2;
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
                case '16' : // Closure -> Win
                    ProspectDocuments::where('id', $request->offer_document)->update(['win' => 1]);
                    $update_status = array('status_id' => 31);
                    break;
                case '17' : // Lose
                    $update_status = array('status_id' => 37);
                    break;
                case '12' : //Offer
                    $update_status = array('status_id' => 32);
                    break;
                case '18' : // Order -> Execution
                case '19' : // Order -> Commission
                    $update_status = array('status_id' => 34);
                    break;
                default :
                    $update_status = array('status_id' => 31);
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
            'status_id' => 35,
        ]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => 35,
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
            'status_id' => 36,
        ]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => 36,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status updated Successfully']);
    }

    /**
     * GA Head Approve
     */
    public function gaApprove(Request $request, $id)
    {
        $prospect = Prospects::find($id);
        $offer_type_docs = ProspectDocuments::where(['document_type_id' => 2, 'prospect_id' => $id])->whereNULL('status')->get();
        return view('spot.prospects.show', [
            'type' => 8,
            'id' => $id,
            'prospect' => $prospect,
            'offer_type_docs' => $offer_type_docs,
        ]); 
    }

    /**
     * Ga Head Submit
     */
    public function gaHeadSubmit(Request $request , $id)
    {
        $request->validate([
            'offer_document' => 'required',
            'notes' => 'required',
            'approval_status' => 'required',
        ]);
        if($request->has('approval_status') and $request->approval_status == "2") {
            // Offer Rejected
            // Update Documents and prospects
            $updateDoc = ProspectDocuments::where('id', $request->offer_document)->update(['status' => 2]);
            $updateProspectStatus = Prospects::where('id', $id)->update(['status_id' => 31]);
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id' => $id,
                'stage_id' => 38,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            return response()->json(['success' => 'Document rejected Successfully']);
        }else {
            // Update Prospect Document Status
            $updateDoc = ProspectDocuments::where('id', $request->offer_document)->update(['status' => 1]);
            // Update Prospects Table
            $updateProspectStatus = Prospects::where('id', $id)->update(['status_id' => 31]);
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id' => $id,
                'stage_id' => 33,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            // Update Prospect Approval
            ProspectApproval::where('prospect_id', $id)->where('status_id', 3)->update([
                'status' => 1,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['success' => 'Document approved Successfully']);
        }
    }

    /**
     * To Unhold the Status
     */
    public function unHold(Request $request, $id)
    {
        Prospects::where('id', $id)->update(['status_id' => 31]);
        return response()->json(['msg' => 'Status updated successfully']);
    }
}