<?php

namespace App\View\Components\Master;

use App\Enums\Department as EnumsDepartment;
use App\Enums\Role;
use App\Models\Master\Department;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class DepartmentFilter extends Component
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
        $allowed_roles = [
            Role::ACTIVATION->value, 
            Role::HSE->value, 
            Role::MARKETING->value, 
            Role::GI_ENGINEER->value, 
            Role::ADMIN->value, 
            Role::SUPER_ADMIN->value,
            Role::DELIVERY_MANAGER->value,
        ];

        // Get all departments
        if(Auth::user()->roles()->whereIn('role_id', [99,101])->exists()) {
            $departments = Department::where('status', 1)->whereIn('id', [EnumsDepartment::ACTIVATION->value, EnumsDepartment::MARKETING->value, EnumsDepartment::GI->value, EnumsDepartment::HSE->value])->orderBy('name', 'asc')->get();
        }else {
            $departments = Department::where('status', 1)->orderBy('name', 'asc')->get();
        }
        return view('components.master.department-filter', ['departments' => $departments]);
    }
}
