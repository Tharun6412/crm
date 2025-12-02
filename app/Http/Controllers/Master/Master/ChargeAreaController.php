<?php

namespace App\Http\Controllers\Master\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Ca;

class ChargeAreaController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $charge_areas = Ca::all();

        return view('master.charge-areas.list', ['charge_areas' => $charge_areas]);
    }
}