<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::all();
        if($request->ajax())
            return view('admin.teams.list-body',['teams' => $teams]);
        else
            return view('admin.teams.list',['teams' => $teams]);
    }

    public function create()
    {
        $geo_areas = Ga::all();
        $cas = Ca::all();
        $departments = Department::all();
        return view('admin.teams.create',['geo_areas' => $geo_areas,'cas' => $cas,'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ga_id' => 'required',
            'department_id' => 'required',
            'ca_id' => 'required',
        ]);

        $team = Team::create([
        'name' => $request->name,
        'ga_id' => $request->ga_id,
        //'ca_id' => $request->ca_id,
        'department_id' => $request->department_id,
        'created_by' => Auth::id(),
        ]);

        $team->cas()->sync($request->ca_id ?? []);
        return response()->json(['success' => 'Team Created Successfully']);

    }

    public function gaCas(Request $request)
    {
        $cas = Ca::where('ga_id',$request->ga_id)->get();
        return response()->json(['cas' => $cas]);
    }

    public function edit($id)
    {
        $team = Team::with('cas')->findOrFail($id);
        $geo_areas = Ga::all();
        $departments = Department::all();
        $cas = Ca::where('ga_id', $team->ga_id)->get();
        return view('admin.teams.edit',['team' => $team,'geo_areas' => $geo_areas,'cas' => $cas,'departments' => $departments]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ga_id' => 'required',
            'department_id' => 'required',
            'ca_id' => 'required',
        ]);

        $team = Team::findOrFail($id);

        $team->update([
            'name' => $request->name,
            'ga_id' => $request->ga_id,
            'department_id' => $request->department_id,
        ]);

        $team->cas()->sync($request->ca_id ?? []);

        return response()->json([
            'success' => 'Team Updated Successfully'
        ]);
    }
}

