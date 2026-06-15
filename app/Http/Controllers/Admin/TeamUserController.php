<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role as EnumsRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\RoleAction;
use App\Models\Admin\Team;
use App\Models\Admin\User;
use App\Models\Admin\UserStatusHistory;
use App\Models\Admin\UserType;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

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
    /**
     * New user create
     */
    public function createUser()
    {
        $geo_areas = Ga::when((!isAdmin() && !isSuperAdmin() && !isFullAccess()), function($q) {
            $q->whereIn('id', session('user')['gas']);
        })->get();
        $departments = Department::all();
        $types = UserType::all();
        $roles = Role::whereIn('id', [
            EnumsRole::EMPLOYEE->value,
            EnumsRole::EXECUTION->value,
            EnumsRole::ENGINEER->value,
        ])->get();
        return view('admin.teams.users.add',['geo_areas' => $geo_areas,'departments' => $departments,'roles' => $roles,'types' => $types]);
    }
    /**
     * Store user in Users Table
     */
    public function storeUser(Request $request)
    {
        // Validation
        $request->validate([
            'emp_id' => 'required|unique:App\Models\Admin\User,emp_id',
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'department_id' => 'required',
            'type_id' => 'required',
        ]);

        //-- Create New user
        // Insert into users
        $new_user = User::create([
            'emp_id' => $request->emp_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make('Megha@Gas'),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'dob' => ($request->dob) ? Carbon::createFromFormat('d-m-Y', $request->dob) : null,
            'doj' => ($request->doj) ? Carbon::createFromFormat('d-m-Y', $request->doj) : null,
            'department_id' => $request->department_id,
            'type_id' => $request->type_id,
            'status_id' => UserStatus::REGISTER->value,
        ]);

        // Insert into user status history
        UserStatusHistory::create([
            'user_id' => $new_user->id,
            'status_id' => UserStatus::REGISTER->value,
            'created_by' => Auth::id(),
        ]);
        $validated = $request->validate([
            'geo_areas' => 'array',
            'roles' => 'array',
        ]);
        $new_user->ga()->sync($validated['geo_areas'] ?? []);
        $new_user->roles()->sync($validated['roles'] ?? []);


        return response()->json(['success' => 'User Created Successfully']);
    }
}