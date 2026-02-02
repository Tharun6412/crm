<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\Admin\UserStatusHistory;
use Illuminate\Support\Facades\Auth;

class changePassword extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('auth.change_password');
    }

    /**
     * Update password
     */
    public function store(ChangePasswordRequest $request)
    {
        $request->updatePassword();
        // Create status history record
        UserStatusHistory::create([
            'user_id' => Auth::id(),
            'status_id' => UserStatus::RSET_PASSWORD->value,
            'created_by' => Auth::id(),
        ]);
        
        // Redirect success
        return redirect('/profile')->with('status', 'Password changed successfully!');
    }
}