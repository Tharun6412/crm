<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Package;
use App\Models\Admin\Module;
use App\Models\Admin\ModuleUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Modules controller
 */
class ModuleController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get modules
        // $modules = Module::with('moduleUrls')->get();
        // $modules = Module::orderBy('position')->get()->toArray();
        // Get modules recursively with parent-child relation
        $modules = Module::with('recursiveChilds')->whereNull('parent_id')->orderBy('position')->get();
        // dd($modules);
        return view('admin.modules.module_list', ['modules' => $modules]);
    }

    /**
     * View details of module
     */
    public function show($id)
    {
        // 
    }

    /**
     * Create
     */
    public function create()
    {
        // 
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'package_id' => 'required',
            'url' => 'required',
            'code' => 'required',
            'icon' => 'required',
            'status' => 'required',
            'position' => 'required',
        ]);
        // 
        $parent = $request->has('parent_id') ? $request->parent_id : $request->parent;
        // Insert module
        $new_module = Module::create([
            'name' => $request->name,
            'package_id' => $request->package_id,
            'url' => $request->url,
            'slug' => $request->code,
            'icon' => $request->icon,
            'status' => $request->status,
            'position' => $request->position,
            'parent_id' => $parent,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Module created successfully!']);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        $module = Module::find($id);
        // Get additional data
        $packages = Package::all();
        return view('admin.modules.edit', [
            'module' => $module,
            'packages' => $packages,
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
        ]);

        // Update database
        $update_module = Module::where('id', $id)->update([
            'name' => $request->name,
            'package_id' => $request->package_id,
            'url' => $request->url,
            'slug' => $request->code,
            'icon' => $request->icon,
            'status' => $request->status,
            'position' => $request->position,
            'parent_id' => $request->parent,
        ]);

        // Delete module URLS
        $update_mod_urls = ($request->module_url) ? $request->module_url : [];
        if($request->has('delete_url')) {
            // Delete from database
            $delete_urls = ModuleUrl::destroy($request->delete_url);
            // Remove deleted elements from update array
            $update_mod_urls = array_diff_key($request->module_url, $request->delete_url);
            // print_r($update_mod_urls);
        }
        // Update module URLs
        if(sizeof($update_mod_urls) > 0) {
            $update_urls = [];
            foreach($update_mod_urls as $key => $url) {
                $update_urls[] = [
                    'id' => $key,
                    'name' => $request->module_name[$key],
                    'module_id' => $id,
                    'url' => $url,
                ];
            }
            // Update in database
            // print_r($update_urls);
            $update_model = ModuleUrl::upsert($update_urls, uniqueBy: ['id'], update: ['name', 'module_id', 'url']);
        }
        // Add new modules URLS
        if($request->has('new_mod_name')) {
            $new_urls = [];
            foreach ($request->new_mod_url as $key => $url) {
                $new_urls[] = [
                    'name' => $request->new_mod_name[$key],
                    'module_id' => $id,
                    'url' => $url,
                ];
            }
            // print_r($new_urls);
            $insert_model = ModuleUrl::insert($new_urls);
        }

        // Response
        return response()->json(['success' => 'Module details updated successfully!']);
    }

    /**
     * Edit URLs
     */
    public function editUrls($id)
    {
        $module = Module::find($id);
        return view('admin.modules.edit-urls', ['module' => $module]);
    }

    /**
     * Add submodule
     */
    public function createSub($parent)
    {
        // Get additional data
        $packages = Package::all();
        return view('admin.modules.create', [
            'parent' => $parent,
            'packages' => $packages,
        ]);
    }
}