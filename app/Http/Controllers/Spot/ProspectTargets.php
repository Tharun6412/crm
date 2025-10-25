<?php
namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;

class ProspectTargets extends Controller
{
    public function index()
    {
       return view('spot.prospects.targets.list');
    }
}