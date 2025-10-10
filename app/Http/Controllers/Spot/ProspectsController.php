<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cluster;
use App\Models\Admin\FirmTypes;
use App\Models\Admin\FuelTypes;
use App\Models\Admin\Ga;
use App\Models\Admin\IndustrialAreas;
use App\Models\Spot\Prospects;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProspectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prospects = Prospects::all();
        return view('spot.prospects.list', ['prospects' => $prospects]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $geo_areas = Ga::all();
        $clusters = Cluster::all();
        $firm_types = FirmTypes::all();
        $fuel_types = FuelTypes::all();
        return view('spot.prospects.create', [
            'geo_areas' => $geo_areas,
            'clusters' => $clusters,
            'firm_types' => $firm_types,
            'fuel_types' => $fuel_types,
            'industrial_areas' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ga_id' => 'required',
            'name' => 'required',
            'segment_id' => 'required',
            'firm_id' => 'required',
            'fuel_id' => 'required',
        ]);
        $stage = 1;
        $status = 7;
        $sub_stage_id =13;

        $ga_val = Ga::find($request->ga_id); 
        // TO insert into the Vehicle
        $add_prospect = Prospects::create([
            'name' => $request->name,
            'firm_id' => $request->firm_id,
            'fuel_id' => $request->fuel_id,
            'fuel_consumption' => $request->fuel_consumption,
            'unit_id' => $request->unit_id,
            'potential' => $request->potential,
            'expected_date' =>  Carbon::createFromFormat('d-m-Y', $request->expected_date) ?? null,
            'zone' => $request->zone,
            'status' => $status,
            'stage' => $stage,
            'sub_stage_id' => $sub_stage_id,
            'status_date' => date('Y-m-d H:i:s'),
            'ga_id' => $request->ga_id,
            'state_id' => $ga_val['state_id'],
            'cluster_id' => $ga_val['cluster_id'],
            'industrial_area_id' => $request->industrial_area_id,
            // 'ga_head'          => $this->input->post('ga_head'),
            // 'cluster_head'     => $this->input->post('cluster_head'),
            // 'sales_officer' => $this->input->post('sales_officer'),
            'segment_id' => $request->segment_id,
            'notes' => $request->notes,
            'pipeline_availability' => $request->pipeline_availability,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'created_by' => 1,
        ]);
        $prospect_code = 'SP' . date('ym') . str_pad($add_prospect->id, 4, '0', STR_PAD_LEFT);
        Prospects::where('id', $add_prospect->id)->update();
        // Response Message
        return response()->json(['success' => 'Prospect Details Created Successfully']);
    }

    /**
     * Get Industrial Area Based on GA
     */
    public function getIndustrialAreaByGA(Request $request)
    {
        $industrial_areas = IndustrialAreas::where('ga_id', $request->ga_id)->get();
        return response()->json(['industrial_areas' => $industrial_areas]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
