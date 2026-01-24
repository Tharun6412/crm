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
        $complaints = Complaint::select('status_id', DB::raw('COUNT(id) as status_count'))->groupBy('status_id')->get()->pluck('status_count','status_id');
        $consumers = Consumer::select('segment_id', DB::raw('COUNT(id) as segment_count'))->groupBy('segment_id')->get()->pluck('segment_count','segment_id');
        // dd($complaints);
        return view('complaints.dashboard.dashboard', [
            'complaints' => $complaints,
            'consumers' => $consumers,
        ]);
    }
}