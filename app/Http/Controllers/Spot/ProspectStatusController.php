<?php
namespace App\Http\Controllers\Spot;

use App\Enums\DocumentType;
use App\Enums\SpotStages;
use App\Enums\SpotStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Spot\ProspectApproval;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Stage;
use App\Models\Spot\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProspectStatusController extends Controller
{
    /**
     * Display the Status Editing Form
     * Details to be loaded: sub_stages, status_list, prospect, offerTypeDocuments(only if sub_stage=WIN)
     * type = 1 (status-history)
     * 
     * @return view
     */
    public function editStatus(Request $request, $id)
    {
        $status_list = Stage::where('type', 1)->where('parent_id', NULL)->get();
        $prospect = Prospects::find($id);
        $sub_stages = Stage::where('parent_id', $prospect->stage->parent->id)->get();
        if($prospect->stage_id == SpotStages::WIN->value) {
            $offer_type_docs = ProspectDocuments::where(['document_type_id' => DocumentType::OFFER->value, 'prospect_id' => $id, 'status' => 1])->get();
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
        $sub_stages = Stage::where('parent_id', $request->stage_id)->get();
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
        if($sub_stage_id == SpotStages::WIN->value) {
            $offer_type_docs = ProspectDocuments::where(['document_type_id' => DocumentType::OFFER->value, 'prospect_id' => $prospect_id, 'status' => 1])->get();
        }
        return view('spot.prospects.status-history.sub_stage_details', [
            'sub_stage_id' => $sub_stage_id,
            'prospect' => $prospect,
            'prospect_id' => $prospect_id,
            'offer_type_docs' => $offer_type_docs ?? [],
        ]);
    }

    /**
     * Updating the Status based on the sub stage
     * 
     * This Method:
     * 1.Validate required input fields
     * 2.Uploads required documents based on sub stages(Technical/ Offer)
     * 3.Maps the status based on sub stage
     * 4.Updates related tables.
     * 
     * Conditional Fields:
     * -expected date (required for technical stage)
     * -offer_document (required for win stage)
     * 
     * @return response string
     */
    public function updateStatus(Request $request , $id)
    {
        $rules = [
            'stage_id' => 'required',
            'sub_stage_id' => 'required',
            'notes' => 'required',
        ];
        switch($request->sub_stage_id) {
            case SpotStages::TECHNICAL->value :
                $rules['expected_date'] = 'required';
                break;
            case SpotStages::WIN->value :
                $rules['offer_document'] = 'required';
                break;
            default:
                echo "";
        }
        $request->validate($rules);
        switch($request->sub_stage_id) {
            case SpotStages::TECHNICAL->value:
            case SpotStages::OFFER->value:
                $doc_type = $request->sub_stage_id == SpotStages::TECHNICAL->value ? DocumentType::LOAD_ASSESSMENT_SHEET->value : DocumentType::OFFER->value;
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
            'stage_id' => $request->sub_stage_id,
            'status_date' => Carbon::now(),
        );
        if($request->sub_stage_id == SpotStages::TECHNICAL->value)
        {
            $update_status_list['expected_date'] = Carbon::createFromFormat('d-m-Y', $request->expected_date);
        }
        // Update to Prospects Table
        $statusUpdate = Prospects::where('id', $id)->update($update_status_list);
        if($statusUpdate) {
            // Update status based on stage
            switch($request->sub_stage_id) {
                case SpotStages::WIN->value : // Closure -> Win
                    ProspectDocuments::where('id', $request->offer_document)->update(['win' => 1]);
                    $update_status = array('status_id' => SpotStatus::CLOSED_WON->value);
                    break;
                case SpotStages::LOSE->value : // Lose
                    $update_status = array('status_id' => SpotStatus::CLOSED_LOST->value);
                    break;
                case SpotStages::OFFER->value : //Offer
                    $update_status = array('status_id' => SpotStatus::REQUEST_FOR_APPROVAL->value);
                    break;
                case SpotStages::EXECUTION->value : // Order -> Execution
                case SpotStages::COMMISSION->value : // Order -> Commission
                    $update_status = array('status_id' => SpotStatus::CLOSED_WON->value);
                    break;
                default :
                    $update_status = array('status_id' => SpotStatus::IN_PROGRESS->value);
            }
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id'        => $id,
                'stage_id' => $request->sub_stage_id,
                'status_id' => $update_status['status_id'],
                'notes'          => $request->notes,
                'created_at'       => Carbon::now(),
                'created_by'       => Auth::id(),
            ]);
            // Status Update Query for Prospects 
            if($update_status['status_id'] > 0) {
                Prospects::where('id', $id)->update($update_status);
            }
            return response()->json(['success' => 'Status updated Successfully']);
        }
    }
    /**
     * Display the Hold-status Form in screen
     * Loads the prospect details
     * type=6 (Hold)
     * 
     * @return view
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
     * 
     * Validation is required(notes)
     * Update Tables:
     * ->Prospect
     * ->ProspectStatusHistory
     * 
     * @return response string
     */
    public function updateHoldStatus(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        $prospect = Prospects::find($id);
        // Update the status in Prospects
        $prospect->update(['status_id' => SpotStatus::HOLD->value]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => $prospect->stage_id,
            'status_id' => SpotStatus::HOLD->value,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status updated Successfully']);
    }

    /**
     * Display the Cancel Status Form in Screen
     * Loads the Prospect Details
     * type = 7 (Cancel)
     * 
     * @return view
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
     * 
     * Validation : notes is required
     * Update Tables:
     * ->Prospect
     * ->ProspectStatusHistory
     * 
     * @return response string
     */
    public function updateCancelStatus(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        $prospect = Prospects::find($id);
        // Update the status in Prospects
        $prospect->update(['status_id' => SpotStatus::CANCEL->value]);
        // Insert into Status History
        ProspectStatusHistory::create([
            'prospect_id' => $id,
            'stage_id' => $prospect->stage_id,
            'status_id' => SpotStatus::CANCEL->value,
            'notes' => $request->notes,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Status updated Successfully']);
    }

    /**
     * Display the Screen
     * Fetch the Prospect Details
     * Loads the documents only with offer type.
     * type = 8 (GaApproval)
     * 
     * @return view
     */
    public function gaApprove(Request $request, $id)
    {
        $prospect = Prospects::find($id);
        $offer_type_docs = ProspectDocuments::where(['document_type_id' => DocumentType::OFFER->value, 'prospect_id' => $id])->whereNULL('status')->get();
        return view('spot.prospects.show', [
            'type' => 8,
            'id' => $id,
            'prospect' => $prospect,
            'offer_type_docs' => $offer_type_docs,
        ]); 
    }

    /**
     * Handle the GA Approval or Rejection
     * If Approval Status = 2 -> Rejected
     * (Otherwise -> Approved)
     * Update Tables:
     * ->ProspectDocuments
     * ->ProspectDetails
     * ->ProspectStatusHistory
     * ->ProspectApproval (Only if Approved)
     * 
     * @return response string
     */
    public function gaHeadSubmit(Request $request , $id)
    {
        $request->validate([
            'offer_document' => 'required',
            'notes' => 'required',
            'approval_status' => 'required',
        ]);
        // Fetch Prospect Details
        $prospect = Prospects::find($id);
        if($request->has('approval_status') and $request->approval_status == "2") {
            // Offer Rejected
            // Update Documents and prospects
            $updateDoc = ProspectDocuments::where('id', $request->offer_document)->update(['status' => 2]);
            $updateProspectStatus = $prospect->update(['status_id' => SpotStatus::IN_PROGRESS->value]);
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id' => $id,
                'stage_id' => $prospect->stage_id,
                'status_id' => SpotStatus::REJECTED->value,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            // Response
            return response()->json(['success' => 'Document rejected Successfully']);
        }else {
            // Update Prospect Document Status
            $updateDoc = ProspectDocuments::where('id', $request->offer_document)->update(['status' => 1]);
            // Update Prospects Table
            $updateProspectStatus = $prospect->update(['status_id' => SpotStatus::APPROVED->value]);
            // Insert into Status History
            ProspectStatusHistory::create([
                'prospect_id' => $id,
                'stage_id' => $prospect->stage_id,
                'status_id' => SpotStatus::APPROVED->value,
                'notes' => $request->notes,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            // Update Prospect Approval
            ProspectApproval::where('prospect_id', $id)->where('status_id', SpotStatus::APPROVED->value)->update([
                'status' => 1,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['success' => 'Document approved Successfully']);
        }
    }

    /**
     * To Unhold the Status
     * - Updates the status_id from [Hold -> InProgress] in Prospects table
     * 
     * @return response string
     */
    public function unHold(Request $request, $id)
    {
        Prospects::where('id', $id)->update(['status_id' => SpotStatus::IN_PROGRESS->value]);
        return response()->json(['msg' => 'Status updated successfully']);
    }
}