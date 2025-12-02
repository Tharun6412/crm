<?php

namespace App\Http\Controllers\Master\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Cluster;

class ClusterController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        $clusters = Cluster::all();

        return view('master.clusters.list', ['clusters' => $clusters]);
    }
}