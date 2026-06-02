<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\SubArea;
use Illuminate\Http\Request;

class SubAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subareas = SubArea::with([
            'area.ca.ga',
            'area.ca.district',
            'area.ca',
        ])
        ->when($request->filled('key'), function($q) use ($request) {
            $q->where('name','like','%'.$request->key.'%');
        })
        ->when($request->has('geo_area'), function($q) use ($request) {
            $q->whereHas('area.ca',function($q) use ($request) {
                $q->whereIn('ga_id', $request->geo_area);
            });
        })
        ->when($request->has('district'), function($q) use ($request) {
            $q->whereHas('area.ca',function($q) use($request){
                $q->whereIn('district_id', $request->district);
            });        
        })
        ->when($request->has('charge_area'),function($q) use ($request) {
            $q->whereHas('area.ca',function($q) use($request) {
                $q->whereIn('ca_id', $request->charge_area);
            });
        })
        ->when($request->has('area'),function($q) use ($request) {
            $q->whereIn('area_id', $request->area);
        })
        ->orderBy('created_at','Desc')
        ->paginate(50)->withQueryString();

        if($request->ajax())
            return view('master.locations.subareas.list-body',['subareas' => $subareas]);
        else
            return view('master.locations.subareas.list',['subareas' => $subareas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $geo_areas = Ga::all();
        return view('master.locations.subareas.create',['geo_areas' => $geo_areas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'area_id' => 'required',
        ]);
        SubArea::create([
            'name' => $request->name,
            'area_id' => $request->area_id,
        ]);
        return response()->json(['success' => 'SubArea created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subarea = SubArea::find($id);
        return view('master.locations.subareas.edit',['subarea' => $subarea]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $subareas = SubArea::find($id);
        $subareas->update([
            'name' => $request->name,
        ]);
        return response()->json(['success' => 'SubArea updated Successfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
}
