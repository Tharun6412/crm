<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticationRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/**
 * Authentication
 */
class Authentication extends Controller
{
    /**
     * Login form
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * New login form
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Login submit
     */
    public function store(AuthenticationRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        //-- Get roles, role modules data and create a session
        // Get role_id, and role_ids from users
        // $auth_user = Auth::user();
        // $roles = explode(',', $auth_user->role_ids);
        // $roles[] = $auth_user->role_id;
        // Get modules / rights from roles
        // $roles_data = Role::whereIn('id', $roles)->get();
        // $rights = '';
        // foreach($roles_data as $role_data) {
        //     $rights .= (!empty($rights)) ? ',' . $role_data->rights : $role_data->rights;
        // }
        // $rights = array_unique(array_filter(explode(',', $rights)));
        // $user = [
        //     'role' => $auth_user->role_id,
        //     'roles' => $roles,
        //     'modules' => $rights,
        // ];
        // Create additional user session 
        // $request->session()->put('user', $user);

        // Routing to home page
        return redirect()->route('home');
    }

    /**
     * Logout
     */
    public function destroy(Request $request)
    {
        $request->session()->invalidate();
        // Destroy additional session
        $request->session()->forget('user');
        $request->session()->regenerateToken();
        // Destroy circular module published cookie
        Cookie::queue(Cookie::forget('published'));

        // Response
        return redirect()->route('home');
    }
}