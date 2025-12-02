<?php

namespace App\Http\Controllers\Master\Master;

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

        return view('master.geo-areas.list', ['geo_areas' => $geo_areas]);
    }
}