<?php

namespace App\Http\Controllers\Master\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;
use App\Models\Master\PriceGroupHistory;
use App\Models\Master\PriceGroups;
use App\Models\Master\Segment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PriceGroupsController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request)
    {
        // Get All price groups
        $price_groups = PriceGroups::
            when($request->has('key'), function($q) use($request) {
                $q->whereAny(['code', 'description'], 'like', '%' . $request->key . '%');
            })
            ->when($request->has('geo_area'), function($q) use($request) {
                $q->whereIn('ga_id', $request->geo_area);
            })
            ->when($request->has('segments'), function($q) use($request) {
                $q->whereIn('segment_id', $request->segments);
            })
            ->paginate(2)->withQueryString();

        // Render output
        if($request->ajax())
            return view('master.consumer.price-groups.list-body', ['price_groups' => $price_groups]);
        else
            return view('master.consumer.price-groups.list', ['price_groups' => $price_groups]);
    }

    /**
     * Create form
     */
    public function create()
    {
        // Get data
        $geo_areas = Ga::all();
        $segments = Segment::all();

        // Render output
        return view('master.consumer.price-groups.create', [
            'geo_areas' => $geo_areas,
            'segments' => $segments,
        ]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'code' => 'required',
            'description' => 'required',
            'ga_id' => 'required',
            'segment_id' => 'required',
            'basic' => 'required|numeric',
            'vat' => 'required|numeric',
            'effective_from' => 'required',
        ]);

        // Insert record
        $new_group = PriceGroups::create([
            'code' => $request->code,
            'description' => $request->description,
            'ga_id' => $request->ga_id,
            'segment_id' => $request->segment_id,
            'basic' => $request->basic,
            'vat' => $request->vat,
            'price' => $request->basic + (($request->basic * $request->vat) / 100),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
        ]);

        // History
        $new_group_history = PriceGroupHistory::create([
            'price_group_id' => $new_group->id,
            'code' => $request->code,
            'description' => $request->description,
            'ga_id' => $request->ga_id,
            'segment_id' => $request->segment_id,
            'basic' => $request->basic,
            'vat' => $request->vat,
            'price' => $request->basic + (($request->basic * $request->vat) / 100),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
        ]);

        // Response
        return response()->json(['success' => 'New price group created successfully!']);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get details
        $price_group = PriceGroups::findOrFail($id);

        // Render output
        return view('master.consumer.price-groups.show', ['price_group' => $price_group]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $price_group = PriceGroups::findOrFail($id);
        $geo_areas = Ga::all();
        $segments = Segment::all();

        // Render output
        return view('master.consumer.price-groups.edit', [
            'price_group' => $price_group,
            'geo_areas' => $geo_areas,
            'segments' => $segments,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'code' => 'required',
            'description' => 'required',
            'ga_id' => 'required',
            'segment_id' => 'required',
            'basic' => 'required|numeric',
            'vat' => 'required|numeric',
            'effective_from' => 'required',
        ]);

        // Insert record
        $update_group = PriceGroups::where('id', $id)->update([
            'code' => $request->code,
            'description' => $request->description,
            'ga_id' => $request->ga_id,
            'segment_id' => $request->segment_id,
            'basic' => $request->basic,
            'vat' => $request->vat,
            'price' => $request->basic + (($request->basic * $request->vat) / 100),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
        ]);
        // History
        $new_group_history = PriceGroupHistory::create([
            'price_group_id' => $id,
            'code' => $request->code,
            'description' => $request->description,
            'ga_id' => $request->ga_id,
            'segment_id' => $request->segment_id,
            'basic' => $request->basic,
            'vat' => $request->vat,
            'price' => $request->basic + (($request->basic * $request->vat) / 100),
            'effective_from' => Carbon::createFromFormat('d-m-Y', $request->effective_from),
        ]);

        // Response
        return response()->json(['success' => 'Price group updated successfully!']);
    }

    /**
     * Destroy
     */
    public function destroy($id)
    {
        try {
            // Delete history and price group
            PriceGroupHistory::where('price_group_id', $id)->delete();
            PriceGroups::destroy($id);
        } catch (\Throwable $th) {
            // Response
            return response()->json(['msg' => 'Failed to delete!']);
        }

        // Response
        return response()->json(['msg' => 'Price group deleted successfully!']);
    }
}