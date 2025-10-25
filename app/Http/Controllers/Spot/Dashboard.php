<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Spot\Prospects;
use App\Models\Spot\ProspectStatusHistory;
use App\Models\Spot\Status;

class Dashboard extends Controller
{
    /**
     * Index Method
     */
    public function index()
    {
        // $data['stages'] = Status::where('type', 1)->where('parent', 0)->get();
        $data['prospects_data'] = Prospects::selectRaw('stage, count(stage) as stage_count, segment_id')->groupByRaw('segment_id, stage')->get();
        // dd($data['prospect_counts']);
        return view('spot.prospects.dashboard.list', $data);
    }
}