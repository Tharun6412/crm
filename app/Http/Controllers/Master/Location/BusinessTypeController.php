<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\BusinessType;
use App\Models\Master\Ga;
use App\Models\Master\IndustrialArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessTypeController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $business_types = BusinessType::when($request->has('key'), function($q) use($request) {
                $q->whereAny(['name'], 'like', '%' . $request->key . '%');
            })->orderByDesc('id')->get();
        // Render view
        if($request->ajax())
            return view('master.locations.business-types.list-body', ['business_types' => $business_types]);
        else
            return view('master.locations.business-types.list', ['business_types' => $business_types]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Create form
        $geo_areas = Ga::all();

        // Render output
        return view('master.locations.industrial-areas.create', ['geo_areas' => $geo_areas]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'ga_id' => 'required',
            'name' => 'required',
        ]);

        // Insert
        $new_ia = IndustrialArea::create([
            'ga_id' => $request->ga_id,
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Industrial area created successfully!']);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $ia = IndustrialArea::findOrFail($id);
        // Render output
        return view('master.locations.industrial-areas.edit', ['ia' => $ia]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'name' => 'required',
        ]);

        // Insert
        $new_ia = IndustrialArea::where('id', $id)->update([
            'name' => $request->name,
        ]);

        // Response
        return response()->json(['success' => 'Industrial area updated successfully!']);
    }
}