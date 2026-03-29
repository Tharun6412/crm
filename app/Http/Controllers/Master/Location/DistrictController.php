<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\District;

class DistrictController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $districts = District::with(['state', 'cluster', 'ga', 'cas'])->get();

        return view('master.locations.districts.list', ['districts' => $districts]);
    }
}