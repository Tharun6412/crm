<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Spot\ProspectComments;
use App\Models\Spot\ProspectDateChangeRequest;
use App\Models\Spot\ProspectDocuments;
use App\Models\Spot\ProspectPipeline;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ProspectDateChangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $date_requests = ProspectDateChangeRequest::orderBy('id', 'desc')->limit(50)->get();
        return view('spot.prospects.date-request.list-body', ['date_requests' => $date_requests]);
    }
    /**
     * To Create a Date Request
     */
    public function create(Request $request, $id)
    {
        $prospect = Prospects::find($id);
        $active_requests = ProspectDateChangeRequest::where('prospect_id', $id)->where('status', 0)->latest()->first();
        if($request->type == "4") {
            return view('spot.prospects.date-request.edit', [
                'type' => 4,
                'id' => $id,
                'prospect' => $prospect,
                'active_requests' => $active_requests,
            ]);    
        }
        return view('spot.prospects.show', [
            'type' => 4,
            'id' => $id,
            'prospect' => $prospect,
            'active_requests' => $active_requests,
        ]);
    }

    /**
     * To store the Date request Details
     */
    public function store(Request $request, $id)
    {
        $new_date = Carbon::parse($request->new_date)->startOfDay();
        $expected_date = Carbon::parse($request->current_expected)->startOfDay();
        $today_date = Carbon::today();
        $request->validate([
            'new_date' => [
                'required', 
                function($attributes, $value, $fail) use($new_date, $expected_date, $today_date, $id) {
                    // Checks Conditions
                    if($new_date->lt($today_date)) {
                        $fail("New Date must be greated than Today's Date");
                    }
                    if($new_date->equalTo($expected_date)) {
                        $fail("New Date and Expected Date must not be the same dates");
                    }
                    // Checks whether pending request exists 
                    $pendingRequest = ProspectDateChangeRequest::where('prospect_id', $id)->where('status', 0)->exists();
                    if ($pendingRequest) {
                        $fail("A request is in process, a new request cannot be accepted.");
                    }
                }
            ],
            'note' => 'required',
        ]);
        // Data Preparation
        $status_val = $new_date->lt($expected_date) ? 1 : 0;
        // To Insert Date Change Request
        $addDateRequest = [
            'prospect_id' => $id,
            'current_date' => $expected_date,
            'new_date' => $new_date,
            'note' => $request->note,
            'status' => $status_val,
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
        ];
        if($status_val == 1) {
            $addDateRequest['approved_by'] = Auth::id();
            $addDateRequest['approved_at'] = Carbon::now();
        }
        ProspectDateChangeRequest::create($addDateRequest);
        return response()->json(['success' => 'Date Request updated Successfully']);
    }

    /**
     * To Approve the Date Request
     */
    public function approve(Request $request)
    {
        // Get the Data By ID
        $dateRequest = ProspectDateChangeRequest::find($request->id);
        // Update Date Request
        ProspectDateChangeRequest::where('id', $request->id)->update([
            'status' => 1,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);
        // Update Prospect
        Prospects::where('id', $request->prospect_id)->update([
            'expected_date' => Carbon::parse($dateRequest->new_date)->toDateString(),
            'updated_at' => Carbon::now(),
        ]);
        Session::flash('success', 'Request approved successfully');
        return response()->json(['success' => 'Request Approved successfully']);
    }

    /**
     * To reject the Date Request
     */
    public function reject(Request $request)
    {
        // Update Date Request
        ProspectDateChangeRequest::where('id', $request->id)->update([
            'status' => 2,
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);
        Session::flash('success', 'Request rejected successfully');
        return response()->json(['success' => 'Request rejected successfully']);   
    }
}