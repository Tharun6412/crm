<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;

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
        // Redirect success
        return redirect('/profile')->with('status', 'Password changed successfully!');
    }
}