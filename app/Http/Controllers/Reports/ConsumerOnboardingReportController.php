<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConnectionType as EnumsConnectionType;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\SegmentType;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
use App\Models\Master\ConnectionType;
use App\Models\Master\District;
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
    public function index(Request $request)
    {
        // Get data
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $segments = Segment::all();
        $connection_types = ConnectionType::all();

        // Get all consumer status counts
        $consumer_status_result = Consumer::selectRaw('ga_id, status_id, count(status_id) as count')
            ->when(($request->has('connect_type_id') AND !empty($request->connect_type_id)), function($q) use($request) {
                $q->where('connection_type_id', $request->connect_type_id);
            })
            ->when(($request->has('onboard_segment_id') AND !empty($request->onboard_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->onboard_segment_id);
            })
            ->groupBy('ga_id', 'status_id')->get();
        
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($consumer_status_result as $row) {
            $consumer_status_counts[$row->ga_id][$row->status_id] = $row->count;
            $consumer_status_sum[$row->status_id] = isset($consumer_status_sum[$row->status_id]) ? $consumer_status_sum[$row->status_id] + $row->count : $row->count;
        }

        if($request->ajax()){
            // Render output
            return view('reports.consumer.onboarding.consumer-status', [
                'geo_areas' => $geo_areas,
                'segments' => $segments,
                'connection_types' => $connection_types,
                'consumer_status_counts' => $consumer_status_counts,
                'consumer_status_sum' => $consumer_status_sum,
            ]);    
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
                $q->where('cns_consumer_status.created_at','>=', $status_date)->where('cns_consumers.created_at', '>=', $status_date);
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

    /**
     * Get Consumer Count By Districts
     */
    public function getCountByDistricts(Request $request)
    {
        $district_count = Consumer::where('ga_id', $request->ga_id)->selectRaw('district_id, status_id, count(status_id) as count')
            ->when(($request->has('connect_type_id') AND !empty($request->connect_type_id)), function($q) use($request) {
                $q->where('connection_type_id', $request->connect_type_id);
            })
            ->when(($request->has('onboard_segment_id') AND !empty($request->onboard_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->onboard_segment_id);
            })
            ->groupBy('district_id', 'status_id')
            ->get();
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($district_count as $row) {
            $consumer_status_counts[$row->district_id][$row->status_id] = $row->count;
            $consumer_status_sum[$row->status_id] = isset($consumer_status_sum[$row->status_id]) ? $consumer_status_sum[$row->status_id] + $row->count : $row->count;
        }
        // Check Connection Type Filter
        switch($request->connect_type_id) {
            case 1:
                $connect_type = EnumsConnectionType::POSTPAID->name; break;
            case 2:
                $connect_type = EnumsConnectionType::PREPAID->name; break;
            default:
                $connect_type = '';
        }
        // Check Segment Filter
        switch($request->onboard_segment_id) {
            case 1:
                $segment = SegmentType::DOMESTIC->name;break;
            case 2:
                $segment = SegmentType::COMMERCIAL->name;break;
            case 3:
                $segment = SegmentType::INDUSTRIAL->name;break;
            default:
                $segment = '';
        }
        $districts = District::where('ga_id', $request->ga_id)->get();
        return view('reports.consumer.onboarding.ga-district-count', [
            'districts' => $districts,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
            'connect_type' => $connect_type,
            'segment' => $segment,
            'request_data' => $request->all(),
        ]);
    }

    /**
     * Get Activated Count By Districts
     */
    public function getActivatedCountByDistricts(Request $request)
    {
        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        $status_date = NULL;
        if($request->filled('status_date')) {
            $status_date = Carbon::parse($request->status_date)->startOfDay();
        }
        $district_count = ConsumerStatus::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->select('cns_consumers.district_id', 'cns_consumer_status.status_id', DB::raw('COUNT(cns_consumer_status.status_id) as count'))
            ->where('cns_consumers.ga_id', $request->ga_id)
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->when(!empty($status_date), function($q) use($status_date) {
                $q->where('cns_consumer_status.created_at','>=', $status_date)->where('cns_consumers.created_at','>=', $status_date);
            })
            ->when(($request->has('connection_type_id') AND !empty($request->connection_type_id)), function($q) use($request) {
                $q->where('cns_consumers.connection_type_id', $request->connection_type_id);
            })
            ->when(($request->has('segment_id') AND !empty($request->segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->segment_id);
            })
            ->groupBy('cns_consumers.district_id', 'cns_consumer_status.status_id')->get();
        $districts = District::where('ga_id', $request->ga_id)->get();
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
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($district_count as $row) {
            $consumer_status_counts[$row->district_id][$row->status_id] = $row->count;
            $consumer_status_sum[$row->status_id] = isset($consumer_status_sum[$row->status_id]) ? $consumer_status_sum[$row->status_id] + $row->count : $row->count;
        }
        return view('reports.consumer.onboarding.ga-district-activate-count', [
            'districts' => $districts,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
            'connect_type' => $connect_type,
            'segment' => $segment,
            'request_data' => $request->all(),
        ]);
    }

    /**
     * Get Cumulative Count for onboarding
     */
    public function getCumulativeConsumerStatusCount(Request $request)
    {
        // Get data
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $segments = Segment::all();
        $connection_types = ConnectionType::all();

        // Get all consumer status counts
        $consumer_status_result = Consumer::selectRaw('ga_id, status_id, count(status_id) as count')
            ->when(($request->has('connect_type_id') AND !empty($request->connect_type_id)), function($q) use($request) {
                $q->where('connection_type_id', $request->connect_type_id);
            })
            ->when(($request->has('onboard_segment_id') AND !empty($request->onboard_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->onboard_segment_id);
            })
            ->groupBy('ga_id', 'status_id')->get();
        
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($consumer_status_result as $row) {
            $consumer_status_counts[$row->ga_id][$row->status_id] = $row->count;
        }
        // Response
        if($request->ajax()){
            // Render output
            return view('reports.consumer.onboarding.consumer-status-count', [
                'geo_areas' => $geo_areas,
                'segments' => $segments,
                'connection_types' => $connection_types,
                'consumer_status_counts' => $consumer_status_counts,
            ]);
        }
        // Render output
        return view('reports.consumer.onboarding.list', [
            'geo_areas' => $geo_areas,
            'segments' => $segments,
            'connection_types' => $connection_types,
            'consumer_status_counts' => $consumer_status_counts,
        ]);
    }

    /**
     * Get District Count Overview
     */
    public function getDistrictsOverviewCount(Request $request)
    {
        $district_count = Consumer::where('ga_id', $request->ga_id)->selectRaw('district_id, status_id, count(status_id) as count')
            ->when(($request->has('connect_type_id') AND !empty($request->connect_type_id)), function($q) use($request) {
                $q->where('connection_type_id', $request->connect_type_id);
            })
            ->when(($request->has('onboard_segment_id') AND !empty($request->onboard_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->onboard_segment_id);
            })
            ->groupBy('district_id', 'status_id')
            ->get();
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($district_count as $row) {
            $consumer_status_counts[$row->district_id][$row->status_id] = $row->count;
        }
        // Check Connection Type Filter
        switch($request->connect_type_id) {
            case 1:
                $connect_type = EnumsConnectionType::POSTPAID->name; break;
            case 2:
                $connect_type = EnumsConnectionType::PREPAID->name; break;
            default:
                $connect_type = '';
        }
        // Check Segment Filter
        switch($request->onboard_segment_id) {
            case 1:
                $segment = SegmentType::DOMESTIC->name;break;
            case 2:
                $segment = SegmentType::COMMERCIAL->name;break;
            case 3:
                $segment = SegmentType::INDUSTRIAL->name;break;
            default:
                $segment = '';
        }
        $districts = District::where('ga_id', $request->ga_id)->get();
        return view('reports.consumer.onboarding.ga-district-overview', [
            'districts' => $districts,
            'consumer_status_counts' => $consumer_status_counts,
            'connect_type' => $connect_type,
            'segment' => $segment,
            'request_data' => $request->all(),
        ]);
    }
}