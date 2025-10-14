<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    /**
     * Edit profile
     */
    public function edit()
    {
        return view('auth.profile_edit');
    }
}