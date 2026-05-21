<?php

namespace App\Http\Controllers\Reports;

use App\Enums\ConnectionType as EnumsConnectionType;
use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Enums\SegmentType;
use App\Exports\Consumers\ConsumerPrepaidExport;
use App\Http\Controllers\Controller;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;
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

        // Get currect active postpaid consumers count GA wise
        $conversion_balance = Consumer::selectRaw('ga_id, COUNT(id) as count')->where([
                'connection_type_id' => EnumsConnectionType::POSTPAID->value,
                'status_id' => EnumsConsumerStatus::ACTIVATE->value
            ])
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->conv_segment_id);
            })
            ->groupBy('ga_id')->get()->pluck('count', 'ga_id')->toArray();
            
        // Get all acheived converions
        $conversion_cumulative = Consumer::selectRaw('cns_consumers.ga_id, COUNT(cns_consumers.id) as count')
            ->join('cns_prepaid', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->whereNotNull('cns_prepaid.conversion_date')
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.ga_id')->get()->pluck('count', 'ga_id')->toArray();
        // Get between acheived converions
        $conversion_between = Consumer::selectRaw('cns_consumers.ga_id, COUNT(cns_consumers.id) as count')
            ->join('cns_prepaid', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->whereNotNull('cns_prepaid.conversion_date')
            ->whereBetween('cns_prepaid.conversion_date', [$from, $to])
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.ga_id')->get()->pluck('count', 'ga_id')->toArray();
        
        // Get Reconnect targets / Balance
        $recon_balance = Consumer::selectRaw('ga_id, COUNT(id) as count')
            ->where('status_id', EnumsConsumerStatus::TD->value)
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->conv_segment_id);
            })
            ->groupBy('ga_id')->get()->pluck('count', 'ga_id')->toArray();
        
        // Get reconnected count cumulative
        $recon_cumulative = Consumer::selectRaw('cns_consumers.ga_id, COUNT(cns_consumers.id) as count')
            ->join('cns_consumer_status', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->where('cns_consumer_status.status_id', EnumsConsumerStatus::RECONNECT->value)
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.ga_id')->get()->pluck('count', 'ga_id')->toArray();
        
        // Get reconnected count between dates
        $recon_between = Consumer::selectRaw('cns_consumers.ga_id, COUNT(cns_consumers.id) as count')
            ->join('cns_consumer_status', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->where('cns_consumer_status.status_id', EnumsConsumerStatus::RECONNECT->value)
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.ga_id')->get()->pluck('count', 'ga_id')->toArray();

        // Render output
        return view('reports.consumer.conversions.list', [
            'geo_areas' => $geo_areas,
            'segments' => $segments,
            'conversion_balance' => $conversion_balance,
            'conversion_cumulative' =>$conversion_cumulative,
            'conversion_between' => $conversion_between,
            'recon_balance' => $recon_balance,
            'recon_cumulative' => $recon_cumulative,
            'recon_between' => $recon_between,
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
            $q->whereNotIn('status_id', [EnumsConsumerStatus::REJECT->value]);
            if($request->filled('ga_id')) {
                $q->where('ga_id', $request->ga_id);
            }
            if ($request->filled('conv_segment_id')) {
                $q->where('segment_id', $request->conv_segment_id);
            }
        })
        ->paginate(50)
        ->withQueryString();
        // Check Segment Filter
        switch($request->conv_segment_id) {
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
        // Get Activated, TD, PD Counts
        $target_consumers = Consumer::selectRaw('district_id, status_id, count(status_id) as count')
            ->where('connection_type_id', EnumsConnectionType::POSTPAID->value)
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('segment_id', $request->conv_segment_id);
            })
            ->where('ga_id', $request->ga_id)
            ->whereIn('status_id', [EnumsConsumerStatus::ACTIVATE->value, EnumsConsumerStatus::TD->value, EnumsConsumerStatus::PD->value])
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('district_id', 'status_id')->get();
        // Reconnect Status Fetch
        $reconnect_status = ConsumerStatus::join('cns_consumers', 'cns_consumers.id', '=', 'cns_consumer_status.consumer_id')
            ->select('cns_consumers.district_id', DB::raw('COUNT(cns_consumer_status.id) as count'))
            ->whereBetween('cns_consumer_status.created_at', [$from, $to])
            ->where('cns_consumers.ga_id', $request->ga_id)
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->where('cns_consumer_status.status_id', EnumsConsumerStatus::RECONNECT->value)
            ->groupBy('cns_consumers.district_id')->get()->pluck('count', 'district_id');

            // print "<pre>"; print_r($reconnect_status);
        $district_count = Prepaid::query()
            ->join('cns_consumers', 'cns_consumers.id', '=', 'cns_prepaid.consumer_id')
            ->select('cns_consumers.district_id', DB::raw('COUNT(DISTINCT cns_consumers.id) as count'))
            ->where('cns_consumers.ga_id', $request->ga_id)
            ->whereBetween('cns_prepaid.conversion_date', [$from, $to])
            ->when(($request->has('conv_segment_id') AND !empty($request->conv_segment_id)), function($q) use($request) {
                $q->where('cns_consumers.segment_id', $request->conv_segment_id);
            })
            ->groupBy('cns_consumers.district_id')->get();
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
        $consumer_status_counts = $consumer_target_counts = $consumer_target_sum = [];
        $consumer_status_sum = 0;
        // Array Preparation
        foreach($target_consumers as $target) {
            $consumer_target_counts[$target->district_id][$target->status_id] = $target->count;
            $consumer_target_sum[$target->status_id] = isset($consumer_target_sum[$target->status_id]) ? $consumer_target_sum[$target->status_id] + $target->count : $target->count;
        }
        foreach($district_count as $row) {
            $consumer_status_counts[$row->district_id] = $row->count;
            $consumer_status_sum += $row->count;
        }
        return view('reports.consumer.conversions.ga-prepaid-count', [
            'districts' => $districts,
            'consumer_target_counts' => $consumer_target_counts,
            'consumer_target_sum' => $consumer_target_sum,
            'segment' => $segment,
            'request_data' => $request->all(),
            'reconnect_status' => $reconnect_status,
            'consumer_status_counts' => $consumer_status_counts,
            'consumer_status_sum' => $consumer_status_sum,
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