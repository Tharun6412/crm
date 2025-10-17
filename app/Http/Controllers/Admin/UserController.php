<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Department;
use App\Models\Admin\Ga;
use App\Models\Admin\Role;
use App\Models\Admin\User;
use Illuminate\Http\Request;
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
            return $q->whereIn('ga_id', $request->geo_area);
        })
        ->when($request->has('departments'), function($q) use ($request) {
            return $q->whereIn('department_id', $request->departments);
        })
        ->when($request->has('roles'), function ($q) use($request) {
            return $q->whereIn('role_id', $request->roles);
        })
        ->when($request->has('status'), function($q) use($request) {
            return $q->whereIn('status', $request->status);
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
     * User edit
     */
    public function edit($id)
    {
        // Get user details
        $user = User::find($id);
        // Get additional data
        $roles = Role::all();
        $departments = Department::all();
        $geo_areas = Ga::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'departments' => $departments,
            'geo_areas' => $geo_areas,
        ]);
    }

    /**
     * Update usuer
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role_id' => 'required',
            'department_id' => 'required',
            'ga_id' => 'required',
            'status' => 'required',
        ]);

        // Update
        $update_user = User::where('id', $id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role_id' => $request->role_id,
            'role_ids' => ($request->has('roles')) ? implode(',', $request->roles) : null,
            'department_id' => $request->department_id,
            'ga_id' => $request->ga_id,
            'status' => $request->status,
            'cluster_restriction' => $request->cluster_restriction,
            'ga_restriction' => $request->ga_restriction,
        ]);
        
        // Response
        return response()->json(['success' => 'User details updated successfully!']);
    }

    /**
     * Reset password
     */
    public function reset(Request $request, $id)
    {
        // Reset password
        $update = User::where('id', $id)->update([
            'password' => Hash::make('Megha@2025'),
        ]);
        // Response
        return response()->json(['msg' => 'Password reset successful!']);
    }
}