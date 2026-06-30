<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\Ga;
use App\Models\Admin\Role;
use App\Models\Admin\User;
use App\Models\Admin\UserStatusHistory;
use App\Models\Admin\UserType;
use App\Models\Master\Ca;
use App\Models\Spot\SpotRoles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Index
     */
    public function index(Request $request): View
    {
        // Sort order
        $sortBy = ($request->get('sortBy')) ? $request->get('sortBy') : 'emp_id';
        $sortOr = ($request->get('sortOr')) ? $request->get('sortOr') : 'asc';

        // Get data form User model
        $users = User::when($request->has('search_key'), function($q) use ($request) {
            $q->where(function($q) use($request) {
                $q->orWhere('emp_id', 'like', '%' . $request->get('search_key') . '%');
                $q->orWhere('first_name', 'like', '%' . $request->get('search_key') . '%');
                $q->orWhere('last_name', 'like', '%' . $request->get('search_key') . '%');
                $q->orWhere('email', 'like', '%' . $request->get('search_key') . '%');
                $q->orWhere('mobile', 'like', '%' . $request->get('search_key') . '%');
            });
        })
        ->when($request->has('geo_area'), function($q) use($request) {
            $q->whereHas('ga', function($q) use($request) {
                return $q->whereIn('ga_id', $request->geo_area);
            });
        })
        ->when($request->has('departments'), function($q) use ($request) {
            return $q->whereIn('department_id', $request->departments);
        })
        ->when($request->has('roles'), function ($q) use($request) {
            $q->whereHas('roles', function($q) use($request) {
                return $q->whereIn('role_id', $request->roles);
            });
        })
        ->when($request->has('status'), function($q) use($request) {
            return $q->whereIn('status_id', $request->status);
        })
        ->orderBy($sortBy, $sortOr)->paginate(10)->withQueryString();

        // Append additional data to pagination
        // $pagination = $users->appends([
        //     'search_key' => $request->get('search_key'),
        //     'sortBy' => $sortBy,
        //     'sortOr' => $sortOr,
        // ]);
        // if($request->has('geo_area')) {
        //     $users->appends(['geo_area' => $request->geo_area]);
        // }
        // if($request->has('departments')) {
        //     $users->appends(['departments' => $request->departments]);
        // }
        // if($request->has('roles')) {
        //     $users->appends(['roles' => $request->roles]);
        // }
        
        // Output rendering
        if($request->ajax())
            return view('admin.users.users_list_body', ['users' => $users]);
        else 
            return view('admin.users.users_list', ['users' => $users]);
    }

    /**
     * User details
     */
    public function show($id)
    {
        // Get user details
        $user = User::find($id);

        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Create New user
     */
    public function create()
    {
        // Get required data to create
        $geo_areas = Ga::all();
        $departments = Department::all();
        $roles = Role::all();
        $types = UserType::all();

        // Render output
        return view('admin.users.create', [
            'geo_areas' => $geo_areas,
            'departments' => $departments,
            'roles' => $roles,
            'types' => $types,
        ]);
    }

    /**
     * Insert user data
     */
    public function store(Request $request)
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
        // Sync Geo areas, roles with pivot relation
        $validated = $request->validate([
            'geo_areas' => 'array',
            'roles' => 'array',
        ]);
        $new_user->ga()->sync($validated['geo_areas'] ?? []);
        $new_user->roles()->sync($validated['roles'] ?? []);

        // Response
        return response()->json(['success' => 'New user created successfully!']);
    }

    /**
     * User edit
     */
    public function edit($id)
    {
        // Get user details
        $user = User::find($id);
        // Get additional data
        $geo_areas = Ga::all();
        $departments = Department::all();
        $roles = Role::all();
        $types = UserType::all();
        // $spot_roles = SpotRoles::all();

        return view('admin.users.edit', [
            'user' => $user,
            'geo_areas' => $geo_areas,
            'departments' => $departments,
            'roles' => $roles,
            'types' => $types,
            // 'spot_roles' => $spot_roles,
        ]);
    }

    /**
     * Update usuer
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'emp_id' => 'required',
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'department_id' => 'required',
            'type_id' => 'required',
        ]);

        // Update
        $user = User::findOrFail($id);
        $user->emp_id = $request->emp_id;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        $user->department_id = $request->department_id;
        $user->type_id = $request->type_id;
        $user->dob = ($request->dob) ? Carbon::createFromFormat('d-m-Y', $request->dob) : null;
        $user->doj = ($request->doj) ? Carbon::createFromFormat('d-m-Y', $request->doj) : null;
        $user->save();

        // Sync Geo areas, roles and SPot roles with pivot relation
        $validated = $request->validate([
            'geo_areas' => 'array',
            'roles' => 'array',
            'spot_roles' => 'array',
        ]);
        $user->ga()->sync($validated['geo_areas'] ?? []);
        $user->roles()->sync($validated['roles'] ?? []);
        // $user->spotRoles()->sync($validated['spot_roles'] ?? []);;
        
        // Response
        return response()->json(['success' => 'User details updated successfully!']);
    }

    /**
     * Reset password
     */
    public function reset($id)
    {
        // Reset password
        $update_user = User::where('id', $id)->update([
            'password' => Hash::make('Megha@Gas'),
        ]);
        // Insert into user status history
        UserStatusHistory::create([
            'user_id' => $id,
            'status_id' => UserStatus::RSET_PASSWORD->value,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['msg' => 'Password reset successful!']);
    }

    /**
     *Manage user by ca edit
     */
    public function editUserCas($id)
    {
        $user = User::with(['cas','ga'])->findOrFail($id);
        $gas = $user->ga->pluck('id');
        $cas = Ca::with(['ga'])->whereIn('ga_id',$gas)->get()->groupBy('ga.name');
        return view('admin.users.manage-cas',['user' => $user,'cas' => $cas]);
    }
    /**
     * Manage user by ca update
     */
    public function updateUserCas(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $user->cas()->sync($request->ca_id ?? []);
        return response()->json(['success' => 'Charge Areas Added Successfully']);
        
    }
}