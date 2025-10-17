<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Module;
use App\Models\Admin\Role;
use Illuminate\Http\Request;

/**
 * Roles controller
 */
class RoleController extends Controller
{
    /**
     * Index method
     */
    public function index()
    {
        // Get all roles
        $roles = Role::all();
        return view('admin.roles.list', ['roles' => $roles]);
    }

    /**
     * Create role
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validartion
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        // Insert
        $new_role = Role::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Response
        return response()->json(['success' => 'Role created successfully!']);
    }

    /**
     * Display roles details
     */
    public function show($id)
    {
        // echo $id;
    }

    /**
     * Edit role and rights
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $modules = Module::with('recursiveChilds')->whereNull('parent_id')->orderBy('position')->get();

        return view('admin.roles.edit', [
            'role' => $role,
            'modules' => $modules,
        ]);
    }

    /**
     * Update 
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'status' => 'required',
        ]);

        // Update role
        $update = Role::where('id', $id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Sync role actions
        $validated = $request->validate([
            'rights' => 'array',
        ]);
        $role = Role::findOrFail($id);
        $role->actions()->sync($validated['rights'] ?? []);

        // Response
        return response()->json(['success' => 'Role details updated successfully!']);
    }
}