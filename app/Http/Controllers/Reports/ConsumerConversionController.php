<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConnectionType as EnumsConnectionType;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\SegmentType;
use App\Exports\Consumers\ConsumerPrepaidExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Prepaid;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsumerConversionController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Validation
        $request->validate([
            'conv_date_from' => 'required',
            'conv_date_to' => 'required',
        ]);
        // Prepare params
        $from = Carbon::parse($request->conv_date_from)->startOfDay();
        $to   = Carbon::parse($request->conv_date_to)->endOfDay();
        // Get data
        $geo_areas = Ga::where('status', 1)->orderBy('position')->get();
        $segments = Segment::all();

        // Get all consumer status counts
        $consumer_status_result = Prepaid::query()
            ->join('cns_consumers', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->when($request->filled('conv_segment_id'), function ($q) use ($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->selectRaw('cns_consumers.ga_id, cns_consumers.status_id, COUNT(DISTINCT cns_consumers.id) as count')
            ->whereBetween('cns_prepaid.conversion_date', [$from, $to])
            ->whereIn('cns_consumers.status_id', [EnumsConsumerStatus::ACTIVATE->value, EnumsConsumerStatus::TD->value, EnumsConsumerStatus::PD->value])
            ->groupBy('cns_consumers.ga_id', 'cns_consumers.status_id')
            ->get();
        // Prepare data
        $consumer_status_counts = [];
        $consumer_status_sum = [];
        foreach($consumer_status_result as $row) {
            $consumer_status_counts[$row->ga_id][$row->status_id] = $row->count;
            $consumer_status_sum[$row->status_id] = isset($consumer_status_sum[$row->status_id]) ? $consumer_status_sum[$row->status_id] + $row->count : $row->count;
        }
        // Render output
        return view('reports.consumer.conversions.list', [
            'geo_areas' => $geo_areas,
            'segments' => $segments,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
        ]);
    }

    /**
     * Index Page
     */
    public function prepaidConsumers(Request $request)
    {
        // Prepare params
        $from = Carbon::parse($request->date_from)->startOfDay();
        $to   = Carbon::parse($request->date_to)->endOfDay();
        // Get data
        $reports = Prepaid::with([
            'consumers.ga',
            'consumers.status',
            'consumers.createdBy'
        ])
        ->whereBetween('conversion_date', [$from, $to])
        ->whereHas('consumers', function ($q) use ($request) {
            $q->where('ga_id', $request->ga_id)
                ->where('status_id', $request->status_id);    

            if ($request->filled('conv_segment_id')) {
                $q->where('segment_id', $request->conv_segment_id);
            }
        })
        ->paginate(50)
        ->withQueryString();
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
            return view('reports.consumer.conversions.prepaid-list-body', [
                'reports' => $reports,
                'request_data' => $request->all(),
                'segment' => $segment,
                'connect_type' => EnumsConnectionType::PREPAID->name,
            ]);
        }
        return view('reports.consumer.conversions.prepaid-list', [
            'reports' => $reports,
            'request_data' => $request->all(),
            'segment' => $segment,
            'connect_type' => EnumsConnectionType::PREPAID->name,
        ]);
    }

    /**
     * Get Consumer Prepaid Count By Districts
     */
    public function getPrepaidCountByDistricts(Request $request)
    {
        // Prepare params
        $from = Carbon::parse($request->conv_date_from)->startOfDay();
        $to   = Carbon::parse($request->conv_date_to)->endOfDay();
        $district_count = Prepaid::query()
            ->join('cns_consumers', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->select('cns_consumers.district_id', 'cns_consumers.status_id', DB::raw('COUNT(DISTINCT cns_consumers.id) as count'))
            ->where('cns_consumers.ga_id', $request->ga_id)
            ->whereBetween('cns_prepaid.conversion_date', [$from, $to])
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.district_id', 'cns_consumers.status_id')->get();
        $districts = District::where('ga_id', $request->ga_id)->get();
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
        return view('reports.consumer.conversions.ga-prepaid-count', [
            'districts' => $districts,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
            'segment' => $segment,
            'request_data' => $request->all(),
        ]);
    }

    /**
     * Consumers Onboarding Export
     */
    public function consumerPrepaidExport(Request $request)
    {
        return (new ConsumerPrepaidExport($request))->download('consumers-prepaid-report.csv');
    }
}