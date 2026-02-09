<?php

namespace App\Http\Controllers\Complaints;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\DocumentCentre\DocumentUpload;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintDocument;
use App\Models\Complaint\ComplaintStatusHistory;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ComplaintMedia;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\ComplaintSegment;
use App\Models\Master\ComplaintType;
use App\Models\Master\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExternalCallsController extends Controller
{
    /**
     * Index Function
     */
    public function index()
    {
        return "External Connection calls";
    }

    /**
     * External Calls create
     */
    public function create()
    {
        $types = ComplaintType::all();
        $media = ComplaintMedia::all();
        $segments = ComplaintSegment::all();
        $priorities = ComplaintPriority::all();
        $states = State::all();
        $categories = ComplaintCategory::whereNull('parent_id')->get();
        return view('complaints.external-create', [
            'types' => $types,
            'media' => $media,
            'segments' => $segments,
            'categories' => $categories,
            'priorities' => $priorities,
            'sub_categories' => [],
            'states' => $states,
            'geo_areas' => [],
            'districts' => [],
        ]);
    }

    /**
     * To Add/Insert the external Complaint
     * 1 = Open
     */
    public function store(Request $request)
    {
        // Form validation
        $request->validate([
            'state_id' => 'required',
            'ga_id' => 'required',
            'district_id' => 'required',
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|numeric|digits:10',
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
        // Data Preparation
        // Complaints
        $add_complaint = Complaint::create([
            'state_id' => $request->state_id,
            'ga_id' => $request->ga_id,
            'district_id' => $request->district_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
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
            $add_document = DocumentUpload::uploadIfPresent($request);
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
        return response()->json(['success' => 'Complaint raised successfully<br/>GO to Calls <a href="'.url('calls').'">List</a>']);
    }
}