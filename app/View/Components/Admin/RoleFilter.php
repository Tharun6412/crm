<?php

namespace App\View\Components\Admin;

use App\Enums\Role as EnumsRole;
use App\Models\Admin\Role;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        $allowed_roles = [EnumsRole::ACTIVATION->value, EnumsRole::HSE->value, EnumsRole::MARKETING->value, EnumsRole::MDPE->value, EnumsRole::GI_ENGINEER->value];
        if(Auth::user()->roles()->whereIn('role_id', $allowed_roles)->exists()) {
            $roles = Role::whereIn('id', $allowed_roles)->get(); 
        }else {
            // Get all roles
            $roles = Role::all();
        }
        return view('components.admin.role-filter', ['roles' => $roles]);
    }
}
