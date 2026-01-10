<?php

namespace App\Http\Controllers\Master\Complaint;

use App\Http\Controllers\Controller;
use App\Models\Master\ComplaintMedia;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\ComplaintSegment;
use App\Models\Master\ComplaintType;
use App\Models\Master\MasterComplaintStatus;

class ContextualDataController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get all the contextual data
        $segments = ComplaintSegment::all();
        $types = ComplaintType::all();
        $media = ComplaintMedia::all();
        $status = MasterComplaintStatus::all();
        $priorities = ComplaintPriority::all();

        // Render output
        return view('master.complaint.contextual_data.list', [
            'segments' => $segments,
            'types' => $types,
            'media' => $media,
            'status' => $status,
            'priorities' => $priorities,
        ]);
    }
}