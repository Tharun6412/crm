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
        $team = Team::with(['users'])->findOrFail($id);
        $users = User::with(['ga', 'department'])
                ->whereHas('ga', function ($q) use ($team) {
                    $q->where('mst_gas.id', $team->ga_id);
                })->get();
        return view('admin.teams.users.create', [
            'team' => $team,
            'users' => $users
        ]);
    }
    /**
     * Based on the team store users
     */
    public function store(Request $request,$id)
    {
        $team = Team::findOrFail($id);
        $team->users()->sync($request->user_id);
        return response()->json(['success' => 'Employees Added Successfully']);
    }
}

