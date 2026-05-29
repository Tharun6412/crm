<?php

namespace App\Http\Controllers\Master\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Master\Department;
use App\Models\Master\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * List of Ticket Categories
     */
    public function index(Request $request)
    {
        $categories = TicketCategory::all();
        if($request->ajax())
            return view('master.tickets.list-body',['categories' => $categories]);
        else
            return view('master.tickets.list',['categories' => $categories]);
    }
    /**
     * Create a Category
     */
    public function create()
    {
        $departments = Department::all();
        return view('master.tickets.create',['departments' => $departments]);
    }
    /**
     * Store Categories
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
        ]);
        TicketCategory::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'created_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Category created Successfully']);
    }
    /**
     * Edit Categories
     */
    public function edit($id)
    {
        $category = TicketCategory::findOrFail($id);
        $departments = Department::all();
        return view('master.tickets.edit',['category' => $category,'departments' => $departments]);
    }
    /**
     * Update Categories
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required',
        ]);
        $category = TicketCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success' => 'Category Updated Successfully']);

    }
}
