<?php
/**
 * Complaints Controller
 */
namespace App\Http\Controllers\Complaints;

use App\Enums\AwsPath;
use App\Enums\ComplaintStatus;
use App\Enums\OtpModule;
use App\Enums\OtpPurpose;
use App\Exports\Complaints\ComplaintExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Admin\User;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintAssign;
use App\Models\Complaint\ComplaintComment;
use App\Models\Complaint\ComplaintDocument;
use App\Models\Complaint\ComplaintStatusHistory;
use App\Models\Consumer\Consumer;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ComplaintMedia;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\ComplaintSegment;
use App\Models\Master\ComplaintType;
use App\Models\Master\Department;
use App\Notifications\Consumer\ComplaintCloseOtpSmsNotification;
use App\Notifications\Consumer\ComplaintRegisterSmsNotification;
use App\Services\OtpService;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintsController extends Controller
{
    /**
     * list of Complaints
     */
    public function index(Request $request)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 50;
        // fetch complaints based on GA
        $complaints = Complaint::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function ($q) {
            $q->whereIn('ga_id', session('user')['gas']);
        })
        ->when($request->filled('key'), function ($q) use($request) {
            $q->whereAny(['code'], 'like', '%' . $request->key . '%');
            $q->orWhereHas('consumer', function ($subQuery) use ($request) {
                $subQuery->where('crn', 'like', '%' . $request->key . '%')
                        ->orWhere('name', 'like', '%' . $request->key . '%');
            });
        })
        ->when($request->has('segment_id'), function($q) use($request) {
            $q->whereIn('segment_id', $request->segment_id);
        })
        ->when($request->has('cmp_status'), function($q) use($request) {
            $q->whereIn('status_id', $request->cmp_status);
        })
        ->when($request->filled('subcategory'), function($q) use($request) {
            $q->whereIn('category_id', $request->subcategory);
        })
        ->when($request->filled('category') && !$request->filled('subcategory'), function($q) use($request) {
            $subIds = ComplaintCategory::whereIn('parent_id', $request->category)->pluck('id');
            $q->whereIn('category_id', $subIds);
        })
        ->when($request->has('geo_area'), function($q) use($request) {
            $q->whereIn('ga_id', $request->geo_area);
        })
        ->when((!empty($request->date_from) and !empty($request->date_to)), function($q) use($request) {
            $q->whereBetween('created_at', [Carbon::createFromFormat('d-m-Y', $request->date_from)->startOfDay()->toDateTimeString(), Carbon::createFromFormat('d-m-Y', $request->date_to)->endOfDay()->toDateTimeString()]);
        })
        ->orderBy($sortBy, $sortOr)->paginate($records)->withQueryString();
        
        // Render output
        if($request->ajax())
            return view('complaints.list-body', ['complaints' => $complaints]);
        else
            return view('complaints.list', ['complaints' => $complaints]);
    }

    /**
     * To Show the Complaint Details
     */
    public function show(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        $reload = $request->has('reload') ? true : false;
        if($reload == true) {
            return view('complaints.comments', ['complaint' => $complaint]);
        }
        return view('complaints.show', ['complaint' => $complaint]);
    }

    /**
     * Relation with Consumer Complaints
     */
    public function consumerComplaints(Request $request, $id)
    {
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'created_at';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'desc';
        $records = ($request->get('records')) ? $request->get('records') : 10;
        $complaints = Complaint::where('consumer_id', $id)->paginate($records)->withQueryString();
        return view('consumers.consumers.show-calls', ['complaints' => $complaints]);
    }
    /**
     * Create a Complaint For Consumer
     * @param int id
     */
    public function create(Request $request, $id)
    {
        $consumer = Consumer::find($id);
        $types = ComplaintType::all();
        $media = ComplaintMedia::all();
        $segments = ComplaintSegment::all();
        $priorities = ComplaintPriority::all();
        $categories = ComplaintCategory::whereNull('parent_id')->get();
        return view('complaints.create', [
            'consumer' => $consumer,
            'types' => $types,
            'media' => $media,
            'segments' => $segments,
            'categories' => $categories,
            'priorities' => $priorities,
            'sub_categories' => [],
        ]);
    }
    /**
     * Get Sub categories List
     */
    public function getSubCategories(Request $request)
    {
        $sub_categories = ComplaintCategory::where('parent_id', $request->category_id)->get();
        return response()->json(['sub_categories' => $sub_categories]);
    }

    /**
     * Get Sub Category Details
     */
    public function getSubCategoryDetails(Request $request)
    {
        // Validate
        $request->validate(['sub_category_id' => 'required']);
        
        $now = Carbon::now();
        $category_details = ComplaintCategory::with(['department', 'type'])->where('id', $request->sub_category_id)->first();
        $resolution_val = (int)$category_details->resolution;
        if($category_details->resolution_type == 1) {
            $est_close_at = $now->addDays($resolution_val);
        }
        else {
            $est_close_at = $now->addHours($resolution_val);
        }
        return response()->json([
            'category_details' => $category_details,
            'estimation_time' => $est_close_at->toDateTimeString(),
        ]);
    }

    /**
     * To Add/Insert the Complaint
     * 1 = Open
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'segment_id' => 'required',
            'type_id' => 'required',
            'media_id' => 'required',
            'category_id' => 'required',
            'priority_id' => 'required',
            'sub_category_id' => 'required',
            'notes' => 'required|max:225', 
        ]);
        $now = Carbon::now();
        $category_details = ComplaintCategory::with(['department', 'type'])->where('id', $request->sub_category_id)->first();
        $resolution_val = (int)$category_details->resolution;
        if($category_details->resolution_type == 1) {
            $est_close_at = $now->addDays($resolution_val);
        }else {
            $est_close_at = $now->addHours($resolution_val);
        }
        $consumer = Consumer::select('ga_id', 'district_id', 'fname', 'lname', 'email' ,'phone')->where('id', $id)->first();
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
        // Complaint Number Generation
        $complaint_number = str_pad($add_complaint->id, 9, "0", STR_PAD_LEFT);
        Complaint::where('id', $add_complaint->id)->update(['code' => $complaint_number]);
        if(!empty($request->dc_file_list)) {
            $add_document = DocumentUpload::uploadIfPresent($request, AwsPath::COMPLAINTS->value);
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
        // Sms Integration
        $sms_response = SmsService::dispatch($consumer, new ComplaintRegisterSmsNotification(['complaint_no' => $complaint_number]));
        return response()->json(['success' => 'Complaint raised successfully'], 200);
    }

    /**
     * To edit the complaint 
     * only status = 1 [Open]
     */
    public function edit(Request $request, $id)
    {
        $types = ComplaintType::all();
        $media = ComplaintMedia::all();
        $segments = ComplaintSegment::all();
        $priorities = ComplaintPriority::all();
        $categories = ComplaintCategory::whereNull('parent_id')->get();
        $complaint = Complaint::find($id);
        $sub_categories = ComplaintCategory::where('parent_id', $complaint->category->parent_id)->get();
        // dd($sub_categories);
        return view('complaints.edit', [
            'complaint' => $complaint,
            'types' => $types,
            'media' => $media,
            'segments' => $segments,
            'categories' => $categories,
            'priorities' => $priorities,
            'sub_categories' => $sub_categories,
        ]);
    }

    /**
     * To Update the Complaint Details
     */
    public function update(Request $request, $id)
    {
        $files = $request->file('dc_file_list', []);
        $count = is_array($files) ? count($files) : 0;
        $request->validate([
            'segment_id' => 'required',
            'type_id' => 'required',
            'media_id' => 'required',
            'category_id' => 'required',
            'priority_id' => 'required',
            'sub_category_id' => 'required',
            'notes' => 'required|max:225',
        ]);
        // Check Complaint Docs
        $docCount = ComplaintDocument::where('complaint_id', $id)->count() + $count;
        if ($docCount > 2) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'dc_file_list_0' => 'Maximum 2 documents are allowed. Please delete existing documents first.'
            ]);
        }
        $now = Carbon::now();
        $category_details = ComplaintCategory::with(['department', 'type'])->where('id', $request->sub_category_id)->first();
        $resolution_val = (int)$category_details->resolution;
        if($category_details->resolution_type == 1) {
            $est_close_at = $now->addDays($resolution_val);
        }else {
            $est_close_at = $now->addHours($resolution_val);
        }
        // Data Preparation
        // Complaints
        $add_complaint = Complaint::where('id', $id)->update([
            'category_id' =>  $request->sub_category_id,
            'description' => $request->notes,
            'segment_id' => $request->segment_id,
            'type_id' => $request->type_id,
            'media_id' => $request->media_id,
            'priority_id' => $request->priority_id,
            'estimated_closed_at' => $est_close_at->toDateTimeString(),
            'updated_by' => Auth::id(),
        ]);
        // Documemnts
        if(!empty($request->dc_file_list)) {
            $add_document = DocumentUpload::uploadIfPresent($request, AwsPath::COMPLAINTS->value);
            foreach($request->dc_file_list as $key => $file) {
                ComplaintDocument::create([
                    'complaint_id' => $id,
                    'file_id' => $add_document['file_list'][$key]['file_id'],
                ]);
            }
        }
        return response()->json(['success' => 'Complaint updated successfully']);
    }
    /**
     * Assign Complaint
     */
    public function assign(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        $departments = Department::all();
        // Render output
        return view('complaints.assign', [
            'complaint' => $complaint,
            'departments' => $departments,
        ]);
    }

    /**
     * Users based on Department
     * @request $department_id
     */
    public function usersListByDepartment(Request $request) {
        $users = User::where('department_id', $request->department_id)->get();
        return response()->json(['users' => $users]);
    }
    /**
     * To Update Assigned user
     * 2 = Assign
     */
    public function assignTo(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'assign_id' => 'required',
            'notes' => 'required',
        ]);
        // Assign Complaint
        ComplaintAssign::create([ 
            'complaint_id' => $id,
            'assigned_to' => $request->assign_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Complaint Status Update
        Complaint::where('id', $id)->update(['status_id' => ComplaintStatus::ASSIGN->value]);
        // Complaint Status History
        ComplaintStatusHistory::create([
            'complaint_id' => $id,
            'status_id' => ComplaintStatus::ASSIGN->value,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Complaint assigned successfully']);
    }

    /**
     * In Progress
     */
    public function inProgress(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        return view('complaints.in-progress', [
            'complaint' => $complaint,
            'status_id' => ComplaintStatus::IN_PROGRESS->value,
        ]);
    }

    /**
     * Investigate
     */
    public function investigate(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        return view('complaints.investigate', [
            'complaint' => $complaint,
            'status_id' => ComplaintStatus::INVESTIGATION->value,
        ]);
    }

    /**
     * Close
     */
    public function close(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        return view('complaints.close', [
            'complaint' => $complaint,
            'status_id' => ComplaintStatus::CLOSE->value,
        ]);
    }

    /**
     * Close OTP
     */
    public function closeOTP(Request $request) 
    {
        // Fetch Complaint Details
        $complaint = Complaint::find($request->id);
        $phone_no = $complaint->consumer->phone ?? $complaint->phone;
        if($complaint->consumer) {
            $consumer_info = $complaint->consumer;
        }else {
            $consumer_info = $complaint;
        }
        if(empty($phone_no)){
            return response()->json('OTP not Sent');
        }
        $otp = OtpService::create($phone_no, OtpPurpose::COMPLAINT_CLOSE->value, OtpModule::USER->value);
        // Sms Integration
        $sms_response = SmsService::dispatch($consumer_info, new ComplaintCloseOtpSmsNotification(['otp' => $otp]));
        return response()->json('OTP Sent Successfully to your mobile number.');
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request) 
    {
        // Session Creation for OTP
        $count = session()->get("complaints.$request->id", 1);
        // OTP Limit if exceeds
        if($count > 2) {
            return response()->json([
                'message' => 'OTP resend limit exceeded',
                'count' => $count,
            ]);
        }
        // Session Updating
        $count = $count+1;
        session()->put("complaints.$request->id", $count);
        // Fetch Complaint Details
        $complaint = Complaint::find($request->id);
        if($complaint->consumer) {
            $consumer_info = $complaint->consumer;
        }else {
            $consumer_info = $complaint;
        }
        $phone_no = $complaint->consumer->phone ?? $complaint->phone;
        if(empty($phone_no)){
            return response()->json(['message' => 'OTP not Sent']);
        }

        $otp = OtpService::create($phone_no, OtpPurpose::COMPLAINT_CLOSE->value, OtpModule::USER->value);
        $sms_response = SmsService::dispatch($consumer_info, new ComplaintCloseOtpSmsNotification(['otp' => $otp]));
        return response()->json([
            'message' => 'OTP Sent Successfully to your mobile number.',
            'count' => $count,
        ]);
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
        $phone_no = $complaint->consumer->phone ?? $complaint->phone;
        if($phone_no) {
            $verify_otp = OtpService::verify($phone_no, OtpPurpose::COMPLAINT_CLOSE->value, $request->otp, OtpModule::USER->value);
        }
        // Stop if OTP is invalid
        if (!$verify_otp) {
            abort(422, 'Invalid otp or OTP expired');
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
        return response()->json(['success' => 'Complaint closed successfully']);
    }

    /**
     * Cancel
     */
    public function cancel(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        return view('complaints.cancel', [
            'complaint' => $complaint,
            'status_id' => ComplaintStatus::CANCEL->value,
        ]);
    }
    /**
     * Status Update
     */
    public function statusChange(Request $request, $id, $status_id)
    {
        $request->validate([
            'notes' => 'required',
        ]);
        // Complaint Status Update
        Complaint::where('id', $id)->update([
            'status_id' => $status_id,
        ]);
        // Complaint Status History
        ComplaintStatusHistory::create([
            'complaint_id' => $id,
            'status_id' => $status_id,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'consumer status updated successfully']);
    }

    /**
     * Relation with Comments
     */
    public function comments(Request $request, $id) 
    {
        $request->validate(['comments' => 'required']);
        $commentable = Auth::user(); // User or Consumer

        $commentable->commentsBy()->create([
            'complaint_id' => $id,
            'comments' => $request->comments,
        ]);
    }

    /**
     * To delete the Comment By ID
     */
    public function deleteComment(Request $request, $id)
    {
        // TO Delete the comment
        ComplaintComment::where('id', $id)->delete();
    }

    /**
     * Complaints Export
     */
    public function complaintExport(Request $request)
    {
        return (new ComplaintExport($request))->download('complaints.xlsx');
    }

    /**
     * Delete Complaint Document
     */
    public function deleteComplaintDocument(Request $request, $id)
    {
        // Delete Complaint Document
        ComplaintDocument::where('file_id', $id)->delete();
        // Delete Dc Files
        DocumentUpload::delete($id);
        return response()->json(['status' => 1]);
    }
}