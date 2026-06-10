<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConnectionType as EnumsConnectionType;
use App\Enums\SegmentType;
use App\Exports\Consumers\ConsumerOnboardExport;
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
        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        $status_date = NULL;
        if($request->filled('status_date')) {
            $status_date = Carbon::parse($request->status_date)->startOfDay();
        }
        // Get data
        $reports = ConsumerStatus::whereHas('consumer', function($q) use($request, $status_date) {
            if($request->filled('connection_type_id')) {
                $q->where('connection_type_id', $request->connection_type_id);
            }
            if($request->filled('segment_id')) {
                $q->where('segment_id', $request->segment_id);
            }
            if($request->filled('ga_id')) {
                $q->where('ga_id', $request->ga_id);
            }
            if(!empty($status_date)) {
                $q->where('created_at','>=', $status_date);
            }
        })
        ->when($request->filled('user_id'), function ($q) use ($request) {
            $q->where('created_by', $request->user_id);
        })
        ->whereBetween('created_at', [$from, $to])
        ->where('status_id', $request->status_id)->orderBy('created_at', 'desc')->paginate(50)->withQueryString();
        // Check Connection Type Filter
        switch($request->connection_type_id) {
            case 1:
                $connect_type = EnumsConnectionType::POSTPAID->name; break;
            case 2:
                $connect_type = EnumsConnectionType::PREPAID->name; break;
            default:
                $connect_type = '';
        }
        // Check Segment Filter
        switch($request->segment_id) {
            case 1:
                $segment = SegmentType::DOMESTIC->name;break;
            case 2:
                $segment = SegmentType::COMMERCIAL->name;break;
            case 3:
                $segment = SegmentType::INDUSTRIAL->name;break;
            default:
                $segment = '';
        }
        // Render output
        if($request->ajax() and $request->page >= 1) {
            return view('reports.consumer.onboarding-status-report.list-body', [
                'reports' => $reports,
                'request_data' => $request->all(),
                'segment' => $segment,
                'connect_type' => $connect_type,
            ]);
        }
        return view('reports.consumer.onboarding-status-report.list', [
            'reports' => $reports,
            'request_data' => $request->all(),
            'segment' => $segment,
            'connect_type' => $connect_type,
        ]);
    }

    /**
     * Consumers Onboarding Export
     */
    public function consumerOnboardExport(Request $request)
    {
        return (new ConsumerOnboardExport($request))->download('consumers-onboard-report.csv');
    }
}