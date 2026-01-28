<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\Segment;
use App\Models\Spot\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetsController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $request->validate([
                'target_year'=> 'required',
            ]);
            $data['target_year'] = $request->target_year;
            $data['y_start'] = Carbon::create($data['target_year'], 4, 1);
            $data['y_end'] = $data['y_start']->copy()->addYear()->subMonth()->endOfMonth();
            $data['geo_areas'] = Ga::all();
            $data['segments'] = Segment::whereIn('id', [2,3])->get(); 
            $data['targets'] = Target::whereBetween('target_date', [$data['y_start'], $data['y_end']])->get();
            return view('spot.targets.list-body', $data);
        }
        // dd($data['targets']);
       return view('spot.targets.list');
    }

    /**
     * Manage Edit/Update Target Data
     */
    public function edit(Request $request, $id)
    {
        $data['y_start'] = Carbon::create($request->target_year, 4, 1);
        $data['y_end'] = $data['y_start']->copy()->addYear()->subMonth()->endOfMonth();
        $data['target_data'] = Target::where('ga_id', $id)->whereBetween('target_date', [$data['y_start'], $data['y_end']])->get();
        $data['ga'] = Ga::find($id);
        $data['segments'] = Segment::whereIn('id', [2,3])->get();
        return view('spot.targets.edit', $data);
    }

    /**
     * Manage Targets Data
     */
    public function manageTargetData(Request $request, $id)
    {
        $request->validate([
            'target_value.*.*' => 'nullable|numeric'
        ]);
        $records = [];
        if($request->has('target_value')) {
            foreach($request->target_value as $target_date => $segments) {
                foreach($segments as $segment_id => $value) {
                    if(isset($value) and $value >= "0") {
                        $records[] = array(
                            'ga_id' => $id,
                            'target_date' => Carbon::createFromFormat('m-Y', trim($target_date))->startOfMonth()->toDateString(),
                            'segment_id' => $segment_id,
                            'target_value' => $value,
                            'created_by' => Auth::id(),
                        );
                    }
                }
            }
            Target::upsert($records, ['ga_id', 'target_date', 'segment_id'], ['target_value']);
            return response()->json(['success' => 'Targets updated successfully']);
        }
    }
}