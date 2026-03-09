<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingStatusReportController extends Controller
{
    /**
     * Index Page
     */
    public function index(Request $request)
    {
        // dd($request->all());
        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        // Get data
        $reports = ConsumerStatus::whereHas('consumer', function($q) use($request) {
            if($request->filled('connection_type_id')) {
                $q->where('connection_type_id', $request->connection_type_id);
            }
            if($request->filled('segment_id')) {
                $q->where('segment_id', $request->segment_id);
            }
            if($request->filled('ga_id')) {
                $q->where('ga_id', $request->ga_id);
            }
        })
        ->whereBetween('created_at', [$from, $to])
        ->where('status_id', $request->status_id)->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        // Render output
        if($request->ajax() and $request->page >= 1) {
            return view('reports.consumer.onboarding-status-report.list-body', ['reports' => $reports]);
        }
        return view('reports.consumer.onboarding-status-report.list', ['reports' => $reports]);
    }
}