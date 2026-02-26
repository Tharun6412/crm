<?php
namespace App\Http\Controllers\Spot;

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
     * Index Method
     */
    public function index(Request $request)
    {
        $data['segments'] = Segment::whereIn('id', [2,3])->orderByDesc('id')->get();
        // Calculate financial year
        $target_year = ($request->has('target_year')) ? $request->target_year : date('Y');
        $data['target_year'] = ($target_year == date('Y') and date('m') < 4) ? $target_year-1 : $target_year;
        $data['y_start'] = Carbon::create($data['target_year'], 4, 1);
        $data['y_end'] = $data['y_start']->copy()->addYear()->subMonth()->endOfMonth();
        
        $data['status_list'] = Stage::where('type', 1)->whereNull('parent_id')->get();
        // Fuel Data Preparation Process
        $data['fuel_types'] = FuelType::all();
        $fuel_raw_data = Prospects::select('segment_id', 'fuel_id', DB::raw('SUM(potential) as fuel_potential'))
            ->when($request->filled('geo_area'), function ($q) use ($request) {
                $q->whereIn('spt_prospects.ga_id', $request->geo_area);
            })
            ->when($request->filled('cluster'), function ($q) use ($request) {
                $q->whereIn('mst_gas.cluster_id', $request->cluster);
            })
            ->groupBy('segment_id', 'fuel_id')->get();
        // Loop the data into Array format
        foreach($fuel_raw_data as $fuel) {
            $data['fuel_data'][$fuel->segment_id][$fuel->fuel_id] = $fuel->fuel_potential;
            $data['total_potential'][$fuel->segment_id] = ($data['total_potential'][$fuel->segment_id] ?? 0) + $fuel->fuel_potential;
            if($fuel->fuelType->fuel_group > 0) {
                $data['group_potential'][$fuel->segment_id][$fuel->fuelType->fuel_group] = ($data['group_potential'][$fuel->segment_id][$fuel->fuelType->fuel_group] ?? 0) + $fuel->fuel_potential;
            }
        }
        // Potential Values 
        $data['potentials'] = Prospects::select('segment_id','status_id', DB::raw('SUM(potential) as total_potential'))
            ->when($request->filled('geo_area'), function ($q) use ($request) {
                $q->whereIn('spt_prospects.ga_id', $request->geo_area);
            })
            ->when($request->filled('cluster'), function ($q) use ($request) {
                $q->whereIn('mst_gas.cluster_id', $request->cluster);
            })
            // ->whereNotIn('status_id', [SpotStatus::HOLD->value, SpotStatus::CANCEL->value, SpotStatus::CLOSED_LOST->value])
            ->groupBy('segment_id','status_id')
            ->get();
        // print "<pre>"; print_r($data['group_potential']); exit;
        // Prospects List
        $data['prospect_data'] = Prospects::select('spt_prospects.segment_id', 'spt_stages.parent_id', DB::raw('COUNT(spt_prospects.stage_id) as status_count'))
            ->join('spt_stages', 'spt_prospects.stage_id', '=', 'spt_stages.id')
            ->leftJoin('mst_gas', 'spt_prospects.ga_id', '=', 'mst_gas.id')
            ->when($request->filled('geo_area'), function ($q) use ($request) {
                $q->whereIn('spt_prospects.ga_id', $request->geo_area);
            })
            ->when($request->filled('cluster'), function ($q) use ($request) {
                $q->whereIn('mst_gas.cluster_id', $request->cluster);
            })
            ->groupBy('spt_prospects.segment_id', 'spt_stages.parent_id')
            ->get();
        // Monthly Targets Data
        $data['targets_data'] = Target::with(['ga'])->select('segment_id', DB::raw('MONTH(target_date) as target_date'), DB::raw('SUM(target_value) as target_value'))
            ->whereBetween('target_date', [$data['y_start'], $data['y_end']])
            ->When($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->when($request->has('cluster'), function($q) use($request) {
                $q->whereHas('ga', function($q2) use($request) {
                    $q2->whereIn('cluster_id', $request->get('cluster'));
                });
            })
            ->groupBy('segment_id', DB::raw('MONTH(target_date)'))->get();
        // Monthly Potential
        $data['potential_data'] = Prospects::with(['ga'])->select('segment_id', DB::raw('MONTH(expected_date) as expected_month'), DB::raw('SUM(potential) as potential'))
            ->whereBetween('expected_date', [$data['y_start'], $data['y_end']])
            ->whereNotIn('status_id', [SpotStatus::HOLD->value, SpotStatus::CANCEL->value, SpotStatus::CLOSED_LOST->value])
            ->When($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->when($request->has('cluster'), function($q) use($request) {
                $q->whereHas('ga', function($q2) use($request) {
                    $q2->whereIn('cluster_id', $request->get('cluster'));
                });
            })
            ->groupBy('segment_id', DB::raw('MONTH(expected_date)'))->get();
        // Monthly Achieved
        $data['achieved_data'] = Prospects::with(['ga'])->select('segment_id', DB::raw('MONTH(expected_date) as expected_month'), DB::raw('SUM(potential) as potential'))
            ->whereBetween('expected_date', [$data['y_start'], $data['y_end']])
            ->where('stage_id', 25)
            ->whereNotIn('status_id', [SpotStatus::HOLD->value, SpotStatus::CANCEL->value, SpotStatus::CLOSED_LOST->value])
            ->When($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->when($request->has('cluster'), function($q) use($request) {
                $q->whereHas('ga', function($q2) use($request) {
                    $q2->whereIn('cluster_id', $request->get('cluster'));
                });
            })
            ->groupBy('segment_id', DB::raw('MONTH(expected_date)'))->get();
        if($request->ajax()) {
            // Render output
            return view('spot.dashboard.list-body', $data);
        }
        else {
            // Render output
            return view('spot.dashboard.list', $data);
        }
    }
}