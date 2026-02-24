<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticationRequest;
use App\Models\Admin\RoleAction;
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
        session()->regenerate();
        
        // Get User roles and respective module actions
        $auth_user = Auth::user();
        // Get user Geo areas, Roles and SPot Roles
        $gas = $auth_user->ga->pluck('id')->toArray();
        $roles = $auth_user->roles->pluck('id')->toArray();
        // Get Module actions from roles
        $module_actions = RoleAction::whereIn('role_id', $roles)->get()->pluck('module_action_id')->toArray();

        // Create additional user session 
        $user = [
            'gas' => $gas,
            'roles' => $roles,
            'module_actions' => $module_actions,
        ];
        session()->put('user', $user);

        // Redirect to intended page or Home
        return redirect()->intended('home');
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

        // Redirect to Home
        return redirect()->route('home');
    }
}