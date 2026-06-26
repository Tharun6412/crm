<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Master\Ga;

class EmployeeReportController extends Controller
{
    /**
     * Index landing
     */
    public function index()
    {
        // Get All GA
        $geo_areas = Ga::where('status', 1)->get();

        return view('reports.employee.list', ['geo_areas' => $geo_areas]);
    }
}