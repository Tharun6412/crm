<?php

namespace App\Http\Controllers\Complaints;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Render output
        return view('complaints.dashboard.dashboard');
    }
}