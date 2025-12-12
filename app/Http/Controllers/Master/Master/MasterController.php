<?php

namespace App\Http\Controllers\Master\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\Module;

class MasterController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get all active modules under Master menu
        $modules = Module::with('recursiveActiveChilds')->where('parent_id', 7)->where('status', 1)->orderBy('position')->get();

        // Render view
        return view('master.master.list', ['modules' => $modules]);
    }
}
