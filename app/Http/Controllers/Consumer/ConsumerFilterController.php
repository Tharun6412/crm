<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Ca;
use App\Models\Master\ConnectionType;
use Illuminate\Http\Request;

class ConsumerFilterController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get consumers
        // dd($request->all());
        $charge_areas = [];
        $areas = [];
        // Charge Areas
        if($request->filled('district')) {
            $charge_areas = Ca::whereIn('district_id', $request->get('district'))->get();
        }
        // Areas
        if($request->filled('charge_area')) {
            $areas = Area::whereIn('ca_id', $request->get('charge_area'))->get();
        }
        // Render output
        return view('consumers.consumers.filter-list', [
            'request' => $request,
            'connection_types' => ConnectionType::all(),
            'charge_areas' => $charge_areas,
            'areas' => $areas,
        ]);
    }
    /**
     * Areas By Charge Area
     */
    public function areaByCA(Request $request)
    {
        $charge_area_ids = $request->charge_area ?? [];
        $areas = Area::whereIn('ca_id', $charge_area_ids)->get();
        return response()->json($areas);
    }
}