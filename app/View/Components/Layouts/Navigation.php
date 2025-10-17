<?php

namespace App\View\Components\Layouts;

use App\Models\Admin\Module;
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
        // Get modules recursively
        $modules_q = Module::with('recursiveActiveChilds')->whereNull('parent_id')->where('status', 1);
        // Display only allocated modules
        if(isAdmin() OR isSuperAdmin()){} else {
            $modules_q->whereIn('id', session()->get('user')['modules']);
        }
        $modules = $modules_q->orderBy('position')->get();
        // Response
        return view('components.layouts.navigation', ['modules' => $modules]);
    }
}
