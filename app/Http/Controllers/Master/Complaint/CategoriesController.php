<?php

namespace App\Http\Controllers\Master\Complaint;

use App\Http\Controllers\Controller;
use App\Models\Master\ComplaintCategory;
use App\Models\Master\ComplaintCategoryType;
use App\Models\Master\ComplaintPriority;
use App\Models\Master\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // Get recursive complaints
        $categories = ComplaintCategory::with('children')->whereNull('parent_id')->orderBy('position')->get();

        // Render output
        return view('master.complaint.categories.list', ['categories' => $categories]);
    }

    /**
     * Create category
     */
    public function create(Request $request)
    {
        $parent_id = $request->parent_id;
        $types = ComplaintCategoryType::all();
        $departments = Department::all();
        $priorities = ComplaintPriority::all();
        
        // Render output
        return view('master.complaint.categories.create', [
            'parent_id' => $parent_id,
            'types' => $types,
            'departments' => $departments,
            'priorities' => $priorities,
        ]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'type_id' => 'required',
           // 'priority_id' => 'required',
            'department_id' => 'required',
            'name' => 'required',
            'resolution' => 'required|numeric',
            'resolution_type' => 'required',
            'position' => 'required',
            'parent_id' => 'required',
        ]);

        // Insert
        $new_category = ComplaintCategory::create([
            'name' => $request->name,
            'resolution' => $request->resolution,
            'resolution_type' => $request->resolution_type,
            'type_id' => $request->type_id,
            'priority_id' => $request->priority_id,
            'department_id' => $request->department_id,
            'parent_id' => ($request->parent_id == 0) ? null : $request->parent_id,
            'position' => $request->position,
            'created_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Category created successfully!']);
    }

    /**
     * Show
     */
    public function show($id)
    {
        // Get the details
        $category = ComplaintCategory::findOrFail($id);

        // Render output
        return view('master.complaint.categories.show', ['category' => $category]);
    }

    /**
     * Edit
     */
    public function edit($id)
    {
        // Get details
        $category = ComplaintCategory::findOrFail($id);
        $types = ComplaintCategoryType::all();
        $departments = Department::all();
        $priorities = ComplaintPriority::all();

        // Render output
        return view('master.complaint.categories.edit', [
            'category' => $category,
            'types' => $types,
            'departments' => $departments,
            'priorities' => $priorities,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'type_id' => 'required',
           // 'priority_id' => 'required',
            'department_id' => 'required',
            'name' => 'required',
            'resolution' => 'required|numeric',
            'resolution_type' => 'required',
            'position' => 'required',
        ]);

        // Insert
        $new_category = ComplaintCategory::where('id', $id)->update([
            'name' => $request->name,
            'resolution' => $request->resolution,
            'resolution_type' => $request->resolution_type,
            'type_id' => $request->type_id,
            'priority_id' => $request->priority_id,
            'department_id' => $request->department_id,
            'parent_id' => $request->parent_id,
            'position' => $request->position,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        // Response
        return response()->json(['success' => 'Category updated successfully!']);
    }
}