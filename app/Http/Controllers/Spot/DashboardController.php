<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Admin\Segment;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Status;
use App\Models\Spot\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Index Method
     */
    public function index(Request $request)
    {
        // $data['stages'] = Status::where('type', 1)->where('parent', 0)->get();
        // $data['prospects_data'] = Prospects::selectRaw('stage, count(stage) as stage_count, segment_id')->groupByRaw('segment_id, stage')->get();
        // dd($data['prospect_counts']);
        $data['prospects_data'] = [];
        $data['segments'] = Segment::whereIn('id', [2,3])->orderByDesc('id')->get();
        $current_date = Carbon::now();
        $data['target_year'] = $current_date->year;
        $data['y_start'] = Carbon::create($data['target_year'], 4, 1);
        $data['y_end'] = $data['y_start']->copy()->addYear()->subMonth()->endOfMonth();
        $data['targets'] = Target::select('segment_id', 'target_date', 'target_value')->whereBetween('target_date', [$data['y_start'], $data['y_end']])->get()->groupBy('segment_id')->map(function($segment_targets) {
           return  $segment_targets->pluck('target_value', 'target_date');
        });
        return view('spot.dashboard.list', $data);
    }
}