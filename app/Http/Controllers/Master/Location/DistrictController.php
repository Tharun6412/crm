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
        $districts = District::all();

        return view('master.locations.districts.list', ['districts' => $districts]);
    }
}