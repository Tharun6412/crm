<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;
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
}