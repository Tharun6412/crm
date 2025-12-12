<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;

class GeoAreaController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $geo_areas = Ga::all();

        return view('master.locations.geo-areas.list', ['geo_areas' => $geo_areas]);
    }
}