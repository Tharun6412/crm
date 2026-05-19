<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Team;
use App\Models\Admin\User;
use Illuminate\Http\Request;

class TeamUserController extends Controller
{
    public function create($id)
    {
        $team = Team::with('users')->findOrFail($id);
        $users = User::where('ga_id',$team->ga_id)->get();
        return view('admin.teams.users.create',['team' => $team, 'users' => $users]);
    }

    public function store(Request $request,$id)
    {
        $team = Team::findOrFail($id);
        $team->users()->sync($request->user_id);
        return response()->json(['success' => 'Employees Added Successfully']);
    }
}

