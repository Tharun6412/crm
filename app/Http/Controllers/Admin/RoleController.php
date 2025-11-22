<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AppModule;
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
        $role = Role::find($id);

        return view('admin.roles.show', ['role' => $role]);
    }

    /**
     * Edit role and rights
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $modules = Module::with('recursiveChilds')->whereNull('parent_id')->orderBy('position')->get();
        $app_modules = AppModule::all();

        return view('admin.roles.edit', [
            'role' => $role,
            'modules' => $modules,
            'app_modules' => $app_modules,
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
            'app_modules' => 'array',
        ]);
        $role = Role::findOrFail($id);
        $role->actions()->sync($validated['rights'] ?? []);
        $role->appModules()->sync($validated['app_modules'] ?? []);

        // Response
        return response()->json(['success' => 'Role details updated successfully!']);
    }
}