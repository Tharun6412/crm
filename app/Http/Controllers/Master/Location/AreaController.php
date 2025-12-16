<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
use App\Models\Master\Ga;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        $areas = Area::when($request->has('key'), function($q) use($request) {
                $q->where('name', 'like', '%' . $request->key . '%');
            })
            ->when($request->has('geo_area'), function ($q) use($request) {
                $q->whereHas('ca', function($q) use($request) {
                    $q->whereIn('ga_id', $request->geo_area);
                });
            })
            ->orderBy('name')
        ->paginate(50);

        // Render view
        if($request->ajax())
            return view('master.locations.areas.list-body', ['areas' => $areas]);
        else
            return view('master.locations.areas.list', ['areas' => $areas]);
    }

    /**
     * Create
     */
    public function create()
    {
        // Create form
        $geo_areas = Ga::all();

        // Render output
        return view('master.locations.areas.create', ['geo_areas' => $geo_areas]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'ca_id' => 'required',
            'name' => 'required',
        ]);

        // Store
        $new_area = Area::create([
            'name' => $request->name,
            'ca_id' => $request->ca_id,
            'status' => 1,
        ]);

        // Response
        return response()->json(['success' => 'Area created successfully!']);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $area = Area::findOrFail($id);

        // Render output
        return view('master.locations.areas.edit', ['area' => $area]);
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

        // Store
        $new_area = Area::where('id', $id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Response
        return response()->json(['success' => 'Area updated successfully!']);
    }
}