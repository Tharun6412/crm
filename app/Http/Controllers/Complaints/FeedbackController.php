<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DocumentCentre\DocumentUpload;
use App\Models\Complaint\Complaint;
use App\Models\Complaint\ComplaintDocument;
use App\Models\Complaint\ComplaintFeedback;
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

class FeedbackController extends Controller
{
    /**
     * Index Function
     */
    public function index()
    {
        return "feedback report";
    }

    /**
     * To create the feedback
     */
    public function edit(Request $request, $id)
    {
        $complaint = Complaint::find($id);
        $types = ComplaintType::all();
        $media = ComplaintMedia::all();
        $segments = ComplaintSegment::all();
        $priorities = ComplaintPriority::all();
        $states = State::all();
        $categories = ComplaintCategory::whereNull('parent_id')->get();
        return view('complaints.feedback.create', [
            'complaint' => $complaint,
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
     * To Add/Insert the Feedback Complaint
     */
    public function update(Request $request, $id)
    {
        // Form validation
        $request->validate([
            'rating' => 'required',
            'notes' => 'required|max:225', 
        ]);
        $collectable = Auth::user();
        $collectable->feedbackBy()->create([
            'complaint_id' => $id,
            'rating' => $request->rating,
            'notes' => $request->notes,
        ]);
        return response()->json(['success' => 'Feedback updated successfully']);
    }
}