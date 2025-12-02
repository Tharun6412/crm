<?php

namespace App\Http\Controllers\Master\Master;

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

        return view('master.states.list', ['states' => $states]);
    }
}