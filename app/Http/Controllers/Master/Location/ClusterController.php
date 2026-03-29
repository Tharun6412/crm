<?php

namespace App\Http\Controllers\Master\Location;

use App\Http\Controllers\Controller;
use App\Models\Master\Cluster;

class ClusterController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $clusters = Cluster::with(['gas'])->get();

        return view('master.locations.clusters.list', ['clusters' => $clusters]);
    }
}