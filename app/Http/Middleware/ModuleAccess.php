<?php

namespace App\Http\Middleware;

use App\Models\Admin\Module;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check logged in user admin or super admin
        if($this->isSuperAdmin($request) OR $this->isAdmin($request)) {
            // Access granted
        }
        else {
            // Get current module url
            $currentUrl = '/' . $request->path();
            // Get Module id
            $module_data = Module::with('moduleActions')->whereHas('moduleUrls', function(Builder $query) use($currentUrl) {
                $query->where('url', 'like', $currentUrl);
            })->orWhere('url', 'like', $currentUrl)->first();
            
            // Check module is listed or not
            if(!$module_data) {
                abort('403', 'Module not found');
            }

            /** 
             * Prepare array of module actions
             * @var array
             */
            $module_actions = $module_data->moduleActions->pluck('id')->toArray();
            
            // User assigned module actions get from Session
            $user_actions = [1, 2, 3, 4, 5, 6, 7, 8];

            // User assigned action on this module
            $user_module_actions = array_intersect($module_actions, $user_actions);

            // Check user has access to this module
            if(count($user_module_actions) > 0) {
                // Access granted
                // Assign all actions of current loading module to request to use in views
                $request->attributes->set('user_module_actions', $module_data->moduleActions->whereIn('id', $user_module_actions)->pluck('slug')->toArray());
            }
            else {
                abort('403', 'Access denied');
            }
        }

        return $next($request);
    }

    /**
     * Check super admin
     */
    public function isSuperAdmin(Request $request)
    {
        return false;
    }

    /**
     * Check admin
     */
    public function isAdmin(Request $request)
    {
        return false;
    }
}
