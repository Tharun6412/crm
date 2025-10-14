<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('admin.packages.list');
    }
}