<?php

namespace App\View\Components\Master;

use App\Models\Master\Department;
use Closure;
use Illuminate\Contracts\View\View;
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
        // Get all departments
        $departments = Department::all();
        return view('components.master.department-filter', ['departments' => $departments]);
    }
}
