<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Ca;
use App\Models\Master\Ga;
use App\Models\Master\IndustrialArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndustrialAreaController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $industrial_areas = IndustrialArea::when($request->has('key'), function($q) use($request) {
                $q->whereAny(['name'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->orderByDesc('id')
            ->paginate(50)->withQueryString();

        // Render view
        if($request->ajax())
            return view('master.locations.industrial-areas.list-body', ['industrial_areas' => $industrial_areas]);
        else
            return view('master.locations.industrial-areas.list', ['industrial_areas' => $industrial_areas]);
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

        // Inserts
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