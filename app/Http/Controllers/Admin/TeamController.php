<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Admin\User;
use App\Models\Master\Ca;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    /**
     * Teams List Page
     */
    public function index(Request $request)
    {
        $teams = Team::with([
            'ga:id,name',
            'departments:id,name',
        ])
        ->withCount(['users as users_count'])
        ->when($request->filled('key'), function($q) use ($request) {
            $q->where('name','like','%'.$request->key.'%');
        })
        ->when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('ga_id',session('user')['gas']);
        })
        ->when($request->has('geo_area'), function($q) use ($request) {
            $q->whereIn('ga_id',$request->geo_area);
        })
        ->when($request->has('departments'), function($q) use ($request) {
            $q->whereIn('department_id',$request->departments);
        })
        ->orderByDesc('created_at')
        ->paginate(20)->withQueryString();

        if($request->ajax())
            return view('admin.teams.list-body',['teams' => $teams]);
        else
            return view('admin.teams.list',['teams' => $teams]);
    }
    /**
     * create Team
     */
    public function create()
    {
        $geo_areas = Ga::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('id',session('user')['gas']);
        })->get();
       // $cas = Ca::all();
        $departments = Department::all();
        
        return view('admin.teams.create',['geo_areas' => $geo_areas,'departments' => $departments]);
    }
    /**
     * Store Team
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ga_id' => 'required',
            'department_id' => 'required',
            'ca_id' => 'required',
            'responsible_user_id' => 'required',
        ]);

        $team = Team::create([
        'name' => $request->name,
        'ga_id' => $request->ga_id,
        'department_id' => $request->department_id,
        'status' => 1,
        'responsible_user_id' => $request->responsible_user_id,
        'created_by' => Auth::id(),
        ]);

        $team->cas()->sync($request->ca_id ?? []);
        return response()->json(['success' => 'Team Created Successfully']);

    }
    /**
     * get ca based on the ga
     */
    public function gaCas(Request $request)
    {
        $cas = Ca::where('ga_id',$request->ga_id)->get();
        $users = User::with(['department'])->where('ga_id',$request->ga_id)->get();
        return response()->json(['cas' => $cas,'users' => $users]);
    }
    /**
     * team edit
     */
    public function edit($id)
    {
        $team = Team::when((!isAdmin() AND !isSuperAdmin() AND !isFullAccess()), function($q) {
            $q->whereIn('ga_id', session('user')['gas']);
        })->with(['cas','ga','departments'])->findOrFail($id);
        // $geo_areas = Ga::all();
        // $departments = Department::all();
        $cas = Ca::where('ga_id', $team->ga_id)->get();
        $users = User::where('ga_id', $team->ga_id)->get();
        return view('admin.teams.edit',['team' => $team,'cas' => $cas,'users' => $users]);
    }
    /**
     * team update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ca_id' => 'required',
        ]);

        $team = Team::findOrFail($id);

        $team->update([
            'name' => $request->name,
            'ga_id' => $request->ga_id,
            'responsible_user_id' => $request->responsible_user_id,
            'department_id' => $request->department_id,
        ]);

        $team->cas()->sync($request->ca_id ?? []);

        return response()->json([
            'success' => 'Team Updated Successfully'
        ]);
    }
    /**
     * charges areas show page
     */
    public function show($id)
    {
        $team = Team::with(['cas','ga','departments','users.roles'])->findOrFail($id);
        return view('admin.teams.show',['team' => $team ]);
    }
    /**
     * status 
     */
    public function toggleStatus($id)
    {
        $team = Team::findOrFail($id);
        $team->status = !$team->status;
        $team->save();

        return response()->json([
            'success' => 'true',
            'message' => 'Status Change Successfully',
            'status' => $team->status ? 'Active' : 'Inactive',
        ]);
    }

}

