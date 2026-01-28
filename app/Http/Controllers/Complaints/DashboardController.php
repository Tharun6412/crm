<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;
use App\Models\Complaint\Complaint;
use App\Models\Consumer\Consumer;
use App\Models\Master\MasterComplaintStatus;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Render output
        $complaints = Complaint::select('status_id', 'segment_id', DB::raw('COUNT(id) as segment_count'), DB::raw('COUNT(id) as status_count'))->groupBy('status_id', 'segment_id')->get();
        return view('complaints.dashboard.dashboard', [
            'complaints' => $complaints,
        ]);
    }
}