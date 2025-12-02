<?php

namespace App\Http\Controllers\Master\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Area;

class AreaController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $areas = Area::all();

        return view('master.areas.list', ['areas' => $areas]);
    }
}