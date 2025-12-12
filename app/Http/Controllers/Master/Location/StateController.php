<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\State;

class StateController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $states = State::all();

        return view('master.locations.states.list', ['states' => $states]);
    }
}