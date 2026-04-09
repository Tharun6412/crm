<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumerOnboardingReportController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get data
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $segments = Segment::all();
        $connection_types = ConnectionType::all();

        // Get all consumer status counts
        $consumer_status_result = Consumer::selectRaw('ga_id, status_id, count(status_id) as count')
            ->groupBy('ga_id', 'status_id')->get();
        
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($consumer_status_result as $row) {
            $consumer_status_counts[$row->ga_id][$row->status_id] = $row->count;
            $consumer_status_sum[$row->status_id] = isset($consumer_status_sum[$row->status_id]) ? $consumer_status_sum[$row->status_id] + $row->count : $row->count;
        }

        // Render output
        return view('reports.consumer.onboarding.list', [
            'geo_areas' => $geo_areas,
            'segments' => $segments,
            'connection_types' => $connection_types,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
        ]);
    }

    /**
     * Report - consumer actvity
     */
    public function activity(Request $request)
    {
        // Validation
        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
        ]);

        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        $status_date = NULL;
        if($request->filled('status_date')) {
            $status_date = Carbon::parse($request->status_date)->startOfDay();
        }
        // Get data
        $consumer_status_result = ConsumerStatus::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->select('cns_consumers.ga_id', 'cns_consumer_status.status_id', DB::raw('COUNT(cns_consumer_status.status_id) as count'))
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->when(!empty($status_date), function($q) use($status_date) {
                $q->where('cns_consumer_status.created_at','>=', $status_date);
            })
            ->when(($request->has('connection_type_id') AND !empty($request->connection_type_id)), function($q) use($request) {
                $q->where('cns_consumers.connection_type_id', $request->connection_type_id);
            })
            ->when(($request->has('segment_id') AND !empty($request->segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->segment_id);
            })
            ->groupBy('cns_consumers.ga_id', 'cns_consumer_status.status_id')->get();
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();

        // Prepare data
        $consumer_status_data = [];
        $status_sum = [];
        foreach($consumer_status_result as $row) {
            $consumer_status_data[$row->ga_id][$row->status_id] = $row->count;
            $status_sum[$row->status_id] = isset($status_sum[$row->status_id]) ? $status_sum[$row->status_id] + $row->count : $row->count;
        }


        // Render output
        return view('reports.consumer.onboarding.consumer-activity', [
            'geo_areas' => $geo_areas,
            'consumer_status_data' => $consumer_status_data,
            'status_sum' => $status_sum,
        ]);
    }
}