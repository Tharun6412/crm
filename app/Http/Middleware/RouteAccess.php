<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RouteAccess middleware will check the module is accessable or not for the current logged in user.
 */
class RouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check the logged in user is SuperAdmin or Admin
        if($this->isSuperAdmin($request) OR $this->isAdmin($request)) {
            // Access granted
        }
        else {
            //-- For others check the module is accessable
            // Get current URL with out parameters and base url
            // $cur_url = ltrim(str_replace(url('/'), '', preg_replace('/\/?[0-9]/', '', url()->current())), '/');
            // Find the module ID with cur_url from module or module urls table
            // $module_data = Module::with('moduleUrls')->whereHas('moduleUrls', function(Builder $query) use($cur_url) {
            //     $query->where('url', 'like', $cur_url);
            // })->orWhere('url', 'like', $cur_url)->first();
            // dd($module_data);
            // 
            // if($module_data) {
            //     $user = $request->session()->get('user');
            //     // Check the module 
            //     if(in_array($module_data->id, $user['modules'])) {
            //         // Access granted
            //     }
            //     else {
            //         // echo 'Access denied';
            //         // return redirect('/');
            //         return response()->view('utils.access-denied');
            //     }
            // }
            // else {
            //     // echo 'Access denied';
            //     // return redirect('/');
            //     return response()->view('utils.module-not-found');
            // }
        }
        // Process next after success
        return $next($request);
    }

    /**
     * Super admin Checking
     */
    public function isSuperAdmin($request)
    {
        // Get role, roles from user session
        // $user = $request->session()->get('user');
        // // Check super admin role
        // if($user['role'] == 1 OR in_array(1, $user['roles'])) {
        //     return true;
        // }
        // else {
        //     return false;
        // }
        return true;
    }

    /**
     * Admin checking
     */
    public function isAdmin($request)
    {
        return true;
        // $user = $request->session()->get('user');
        // // Check admin role
        // if($user['role'] == 2 OR in_array(2, $user['roles'])) {
        //     return true;
        // }
        // else {
        //     return false;
        // }
    }
}
