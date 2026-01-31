<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('reports.dashboard.list');
    }
}