<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
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
        // $data['status_list'] = Status::where('type', 1)->whereNull('parent_id')->get();
        $data['prospect_data'] = Prospects::select('stage_id',DB::raw('COUNT(stage_id) as status_count'))
            ->When($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->get('geo_area'));
            })->when($request->has('cluster'), function($q) use($request) {
                $q->whereHas('ga', function($q2) use($request) {
                    $q2->whereIn('cluster_id', $request->get('cluster'));
                });
            })
            ->groupBy('stage_id')->get();        
        
        if($request->ajax()) {
            // Render output
            return view('spot.dashboard.list-body', $data);
        }
        else {
            // Get Data
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
                ->whereNotIn('status_id', [11,12,26])
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
                ->whereNotIn('status_id', [11,12,26])
                ->When($request->has('geo_area'), function($q) use($request) {
                    $q->whereIn('ga_id', $request->get('geo_area'));
                })->when($request->has('cluster'), function($q) use($request) {
                    $q->whereHas('ga', function($q2) use($request) {
                        $q2->whereIn('cluster_id', $request->get('cluster'));
                    });
                })
                ->groupBy('segment_id', DB::raw('MONTH(expected_date)'))->get();
            // Render output
            return view('spot.dashboard.list', $data);
        }
    }
}