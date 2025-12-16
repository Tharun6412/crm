<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Ca;
use App\Models\Master\Ga;
use Illuminate\Http\Request;

class ChargeAreaController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $charge_areas = Ca::when($request->has('key'), function($q) use($request) {
                $q->whereAny(['code', 'name'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->orderBy('name')
        ->paginate(50);

        // Render view
        if($request->ajax())
            return view('master.locations.charge-areas.list-body', ['charge_areas' => $charge_areas]);
        else
            return view('master.locations.charge-areas.list', ['charge_areas' => $charge_areas]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Create form
        $geo_areas = Ga::all();

        // Render output
        return view('master.locations.charge-areas.create', ['geo_areas' => $geo_areas]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'ga_id' => 'required',
            'district_id' => 'required',
            'code' => 'required',
            'name' => 'required',
        ]);

        // Insert
        $new_ca = Ca::create([
            'code' => $request->code,
            'name' => $request->name,
            'ga_id' => $request->ga_id,
            'district_id' => $request->district_id,
            'status' => 1,
        ]);

        // Response
        return response()->json(['success' => 'Charge area created successfully!']);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $ca = Ca::findOrFail($id);

        // Render output
        return view('master.locations.charge-areas.edit', ['ca' => $ca]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'status' => 'required',
        ]);

        // Insert
        $new_ca = Ca::where('id', $id)->update([
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Response
        return response()->json(['success' => 'Charge area updated successfully!']);
    }
}