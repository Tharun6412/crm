<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Ca;
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
}