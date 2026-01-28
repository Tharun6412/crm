<?php

namespace App\Http\Controllers\Api\V1\Application;

use App\Enums\ComplaintStatus;
use App\Enums\OtpModule;
use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Http\Requests\Api\Consumer\ComplaintValidationRequest;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintDocument;
use App\Models\Complaint\ComplaintStatusHistory;
use App\Models\Consumer\Consumer;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ComplaintMedia;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\ComplaintSegment;
use App\Models\Master\ComplaintType;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintsController extends Controller
{
    /**
     * To Adjust Pagination
     */
    use ApiResponse;
    /**
     * List of complaints
     * @param $consumer_id
     * @method GET
     */
    public function list(Request $request, $id)
    {
        // Complaints list
        $complaints_q = Complaint::with([
            'ga:id,name',
            'category:id,name',
            'type:id,name',
            'media:id,name',
            'priority:id,name',
            'status:id,name' 
        ])
        ->select('id', 'code', 'category_id', 'segment_id', 'priority_id', 'media_id', 'type_id', 'estimated_closed_at', 'closed_at', 'status_id', 'created_at')
        ->when($request->has('key'), function ($q) use($request) {
            $q->whereAny(['code'], 'like', '%' . $request->key . '%');
        })
        ->when($request->has('cmp_status'), function($q) use($request) {
            $q->whereIn('status_id', (array)$request->cmp_status);
        })
        ->where('consumer_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        $complaints = $this->apiPagination($complaints_q);
        // Response
        return response()->json(['complaints' => $complaints], 200);
    }

    /**
     * Create a request and DropDown List
     * @method GET
     */
    public function create(Request $request)
    {
        return response()->json([
            'types' => ComplaintType::select('id', 'name')->get(),
            'media' => ComplaintMedia::select('id', 'name')->get(),
            'segments' => ComplaintSegment::select('id', 'name')->get(),
            'categories' => ComplaintCategory::select('id', 'name')->whereNull('parent_id')->get(),
            'priorities' => ComplaintPriority::select('id', 'name')->get(),
        ], 200);
    }
    /**
     * Store complaints
     * 
     * @method POST
     */
    public function store(ComplaintValidationRequest $request, $id)
    {
        $now = Carbon::now();
        $category_details = ComplaintCategory::select('id', 'resolution', 'resolution_type')->where('id', $request->sub_category_id)->first();
        $resolution_val = (int)$category_details->resolution;
        if($category_details->resolution_type == 1) {
            $est_close_at = $now->addDays($resolution_val);
        }else {
            $est_close_at = $now->addHours($resolution_val);
        }
        $consumer = Consumer::select('ga_id', 'district_id')->where('id', $id)->first();
        // Data Preparation
        // Complaints
        $add_complaint = Complaint::create([
            'consumer_id' => $id,
            'state_id' => $consumer->ga->state_id,
            'ga_id' => $consumer->ga_id,
            'district_id' => $consumer->district_id,
            'category_id' =>  $request->sub_category_id,
            'description' => $request->notes,
            'segment_id' => $request->segment_id,
            'type_id' => $request->type_id,
            'media_id' => $request->media_id,
            'priority_id' => $request->priority_id,
            'estimated_closed_at' => $est_close_at->toDateTimeString(),
            'status_id' => ComplaintStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        $complaint_number = str_pad($add_complaint->id, 9, "0", STR_PAD_LEFT);
        Complaint::where('id', $add_complaint->id)->update(['code' => $complaint_number]);
        if(!empty($request->dc_file_list)) {
            $add_document = DocumentUpload::uploadBulk($request);
            foreach($request->dc_file_list as $key => $file) {
                ComplaintDocument::create([
                    'complaint_id' => $add_complaint->id,
                    'file_id' => $add_document['file_list'][$key]['file_id'],
                ]);
            }
        }
        // Complaint Status
        ComplaintStatusHistory::create([
            'complaint_id' => $add_complaint->id,
            'status_id' => ComplaintStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Complaint raised successfully'], 200);
    }

    /**
     * To Show the Complaint Details
     * @param $complaint_id
     */
    public function show(Request $request, $id)
    {
        $complaint = Complaint::with([
            'ga:id,name',
            'category:id,name,parent_id',
            'category.parent:id,name',
            'type:id,name',
            'media:id,name',
            'priority:id,name',
            'status:id,name',
            'statusHistory',
            'statusHistory.status:id,name',
            'assign',
            'assign.assigned:id,first_name,last_name',
            'comments:id,complaint_id,comments,created_at',
            'complaintDocuments:id,complaint_id,file_id,created_at',
            'complaintDocuments.file:id,doc_number,file_name',
            'feedback:id,complaint_id,rating,notes,created_at',
        ])->select('id', 'code', 'category_id', 'segment_id', 'priority_id', 'media_id', 'type_id', 'estimated_closed_at', 'closed_at', 'status_id', 'created_at')
          ->where('id', $id)->first();
        // Response
        return response()->json([
            'complaint' => $complaint,
        ], 200);
    }

    /**
     * Close OTP
     */
    public function closeOTP(Request $request) 
    {
        if(empty($request->phone_no)){
            return response()->json(['message' => 'OTP not Sent'], 422);
        }
        $otp = OtpService::create($request->phone_no, OtpPurpose::COMPLAINT_CLOSE->value, OtpModule::USER->value);
        return response()->json(['message' => 'OTP Sent Successfully to your mobile number'.$otp], 200);
    }

    /**
     * Close Complaint
     */
    public function closeComplaint(Request $request, $id, $status_id)
    {
        $verify_otp = '';
        $request->validate([
            'notes' => 'required',
            'otp' => 'required',
        ]);
        $complaint = Complaint::find($id);
        if($complaint->consumer->phone) {
            $verify_otp = OtpService::verify($complaint->consumer->phone, OtpPurpose::COMPLAINT_CLOSE->value, $request->otp, OtpModule::USER->value);
        }
        // Stop if OTP is invalid
        if (!$verify_otp) {
            return response()->json(['message' => 'Invalid OTP or OTP Expired'], 422);
        }
        // Complaint Status Update
        $complaint->update([
            'status_id' => $status_id,
            'closed_at' => Carbon::now(),
        ]);
        // Complaint Status History
        ComplaintStatusHistory::create([
            'complaint_id' => $id,
            'status_id' => $status_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Complaint closed successfully'], 200);
    }

    /**
     * Status Update
     * @param $complaint_id, $status_id
     */
    public function statusChange(Request $request, $id, $status_id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Complaint Status Update
        Complaint::where('id', $id)->update([
            'status_id' => $status_id,
            'closed_at' => ($status_id == ComplaintStatus::CLOSE->value) ? Carbon::now() : NULL,
        ]);
        // Complaint Status History
        ComplaintStatusHistory::create([
            'complaint_id' => $id,
            'status_id' => $status_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'consumer status updated successfully'], 200);
    }

    /**
     * Relation with Comments
     * @param $complaint_id
     */
    public function comments(Request $request, $id) 
    {
        $request->validate(['comments' => 'required']);
        $commentable = Auth::user(); // User or Consumer

        $commentable->commentsBy()->create([
            'complaint_id' => $id,
            'comments' => $request->comments,
        ]);
        return response()->json(['data' => 'comment added successfully'], 200);
    }
}