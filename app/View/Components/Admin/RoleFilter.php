<?php

namespace App\View\Components\Admin;

use App\Models\Admin\Role;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoleFilter extends Component
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
        // Get all roles
        $roles = Role::all();
        return view('components.admin.role-filter', ['roles' => $roles]);
    }
}
