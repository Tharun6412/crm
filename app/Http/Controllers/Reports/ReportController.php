<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Admin\Module;

class ReportController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
         // Get all active modules under Master menu
        $modules = Module::with('recursiveActiveChilds', 'children', 'parent')->where('parent_id', 6)->where('status', 1)->orderBy('position')->get();
        return view('reports.dashboard.list', ['modules' => $modules]);
    }
}