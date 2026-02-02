<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\UserStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStatusController extends Controller
{
    /**
     * Edit user status
     */
    public function edit($id)
    {
        // Get user details
        $user = User::find($id);

        return view('admin.users.edit-status', ['user' => $user]);
    }

    /**
     * Update user status
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'status' => 'required',
            'notes' => 'required|max:200',
        ]);

        // Update user status
        User::where('id', $id)->update(['status_id' => $request->status]);
        // Add status history record
        UserStatusHistory::create([
            'user_id' => $id,
            'status_id' => $request->status,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);
        // Response
        return response()->json(['success' => 'User status updated successfully!']);
    }
}