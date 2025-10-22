<?php

namespace App\View\Components\Layouts;

use App\Models\Admin\Module;
use App\Models\Admin\ModuleAction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navigation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // Get modules
        if(isSuperAdmin() OR isAdmin()) {
            // Get all active modules
            $modules = Module::with('recursiveActiveChilds')->whereNull('parent_id')->where('status', 1)->orderBy('position')->get();
        }
        else {
            // Get module Ids from module actions from session
            $module_ids = ModuleAction::selectRaw('DISTINCT(module_id)')->whereIn('id', session('user')['module_actions'])->pluck('module_id')->toArray();
            $modules = [];
            if($module_ids) {
                $modules_p = Module::getParentTree($module_ids);
                // dd($modules_p);
                $modules = Module::buildModuleTree($modules_p);
            }
        }
        // Response
        return view('components.layouts.navigation', ['modules' => $modules]);
    }
}
