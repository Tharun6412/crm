<?php
namespace App\Http\Controllers\Spot;

use App\Enums\SegmentType;
use App\Enums\SpotStages;
use App\Enums\SpotStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\FuelType;
use App\Models\Master\Segment;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Stage;
use App\Models\Spot\Status;
use App\Models\Spot\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Index Method
     *
     * Retrieves and prepares all data needed for the Spot Dashboard, including:
     * - Segments, stages list
     * - Financial year calculation
     * - Potential and fuel data
     * - Monthly targets, potential, and achieved values
     * - Prospects Status count
     *
     * @param Request $request
     * @return view
     */
    public function index(Request $request)
    {
        $data['segments'] = Segment::whereIn('id', [SegmentType::COMMERCIAL->value,SegmentType::INDUSTRIAL->value])->orderByDesc('id')->get();
        // Calculate financial year
        $target_year = ($request->has('target_year')) ? $request->target_year : date('Y');
        $data['target_year'] = ($target_year == date('Y') and date('m') < 4) ? $target_year-1 : $target_year;
        $data['y_start'] = Carbon::create($data['target_year'], 4, 1);
        $data['y_end'] = $data['y_start']->copy()->addYear()->subMonth()->endOfMonth();
        
        $data['status_list'] = Stage::where('type', 1)->whereNull('parent_id')->get();
        // Fuel Data Preparation Process
        $data['fuel_types'] = FuelType::all();
        $fuel_query = Prospects::select('segment_id', 'fuel_id', DB::raw('SUM(potential) as fuel_potential'))
            ->whereBetween('created_at', [$data['y_start'], $data['y_end']]);
        $fuel_query = $this->filterData($fuel_query, $request);
        $data['fuel_raw_data'] = $fuel_query->groupBy('segment_id', 'fuel_id')->get();
        // Potential Values 
        $query = Prospects::select('segment_id','stage_id', DB::raw('SUM(potential) as total_potential'))
            ->whereBetween('spt_prospects.created_at', [$data['y_start'], $data['y_end']]);
        $query = $this->filterData($query, $request);
        $data['potentials'] = $query->groupBy('segment_id','stage_id')->get();
        // Prospects List
        $prospect_query = Prospects::select('spt_prospects.segment_id', 'spt_stages.parent_id', DB::raw('COUNT(spt_prospects.stage_id) as status_count'))
            ->join('spt_stages', 'spt_prospects.stage_id', '=', 'spt_stages.id')
            ->leftJoin('mst_gas', 'spt_prospects.ga_id', '=', 'mst_gas.id')
            ->whereBetween('spt_prospects.created_at', [$data['y_start'], $data['y_end']]);
        $prospect_query = $this->filterData($prospect_query, $request);
        $data['prospect_data'] = $prospect_query->groupBy('spt_prospects.segment_id', 'spt_stages.parent_id')->get();
        // Monthly Targets Data
        $target_query = Target::with(['ga'])->select('segment_id', DB::raw('MONTH(target_date) as target_date'), DB::raw('SUM(target_value) as target_value'))
            ->whereBetween('target_date', [$data['y_start'], $data['y_end']]);
        $targets_query = $this->filterData($target_query, $request);
        $data['targets_data'] = $target_query->groupBy('segment_id', DB::raw('MONTH(target_date)'))->get();
        // Monthly Potential
        $potential_query = Prospects::with(['ga'])->select('segment_id', DB::raw('MONTH(expected_date) as expected_month'), DB::raw('SUM(potential) as potential'))
            ->whereBetween('expected_date', [$data['y_start'], $data['y_end']])
            ->whereNotIn('status_id', [SpotStatus::HOLD->value, SpotStatus::CANCEL->value, SpotStatus::CLOSED_LOST->value]);
        $potential_query = $this->filterData($potential_query, $request);
        $data['potential_data'] = $potential_query->groupBy('segment_id', DB::raw('MONTH(expected_date)'))->get();
        // Monthly Achieved
        $achieved_query = Prospects::with(['ga'])->select('segment_id', DB::raw('MONTH(expected_date) as expected_month'), DB::raw('SUM(potential) as potential'))
            ->whereBetween('expected_date', [$data['y_start'], $data['y_end']])
            ->where('stage_id', SpotStages::COMMISSION->value)
            ->whereNotIn('status_id', [SpotStatus::HOLD->value, SpotStatus::CANCEL->value, SpotStatus::CLOSED_LOST->value]);
        $achieved_query = $this->filterData($achieved_query, $request);
        $data['achieved_data'] = $achieved_query->groupBy('segment_id', DB::raw('MONTH(expected_date)'))->get();
        // Response
        if($request->ajax()) {
            // Render output
            return view('spot.dashboard.list-body', $data);
        }
        else {
            // Render output
            return view('spot.dashboard.list', $data);
        }
    }

    /**
     * Callback Function for filter
     * @param query, request
     */
    public function filterData($query, Request $request) {
        return 
            $query->When($request->filled('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->when($request->filled('cluster'), function($q) use($request) {
                $q->whereHas('ga', function($q2) use($request) {
                    $q2->whereIn('cluster_id', $request->get('cluster'));
                });
            });
    }
}