<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Cluster;
use App\Models\Master\District;
use App\Models\Master\Ga;
use App\Models\Master\State;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $districts = District::with(['state', 'cluster', 'ga', 'cas'])
            ->when($request->filled('key'), function($q) use ($request){
               $q->whereAny(['code','name'],'like','%'.$request->key.'%');
            })
            ->when($request->has('state'),function($q) use ($request){
                $q->whereIn('state_id',$request->state);
            })
            ->when($request->has('cluster'),function($q) use ($request){
                $q->whereIn('cluster_id',$request->cluster);
            })
            ->when($request->has('geo_area'),function($q) use ($request){
                $q->whereIn('ga_id',$request->geo_area);
            })
            ->when($request->has('status'), function($q) use ($request){
                $q->whereIn('status',$request->status);
            })
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();
        
        if($request->ajax())
            return view('master.locations.districts.list-body',['districts' => $districts]);
        else
            return view('master.locations.districts.list', ['districts' => $districts]);
    }
    //create
    public function create()
    {
        $clusters = Cluster::all();
        $states = State::all();
        $geo_areas = Ga::all();
        $districts = District::all();
 
        return view('master.locations.districts.create',['states' => $states,'clusters' => $clusters,'geo_areas' => $geo_areas,'districts' => $districts]);
    }
    //store
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'state_id' => 'required',
            'cluster_id' => 'required',
            'ga_id' => 'required',
        ]);
        District::create([
            'code' => $request->code,
            'name' => $request->name,
            'state_id' => $request->state_id,
            'cluster_id' => $request->cluster_id,
            'ga_id' => $request->ga_id,
            'status' => 1,
        ]);
        return response()->json(['success' => 'District Created Successfully']);
    }
    //edit
    public function edit(Request $request,$id)
    {
        $districts = District::findOrFail($id);
        $clusters = Cluster::all();
        $states = State::all();
        $geo_areas = Ga::all();
        return view('master.locations.districts.edit',['districts' => $districts,'clusters' => $clusters,'states' => $states, 'geo_areas' => $geo_areas]);
    }
    //update
    public function update(Request $request, $id)
    {
        $districts = District::findOrFail($id);
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'state_id' => 'required',
            'cluster_id' => 'required',
            'ga_id' => 'required',
            'status' => 'required',
        ]);
        $districts->update([
            'code' => $request->code,
            'name' => $request->name,
            'state_id' => $request->state_id,
            'cluster_id' => $request->cluster_id,
            'ga_id' => $request->ga_id,
            'status' => $request->status,
        ]);
        return response()->json(['success' => 'District Updated Successfully']);
    }
} 