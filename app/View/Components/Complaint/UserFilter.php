<?php

namespace App\View\Components\Complaint;

use App\Enums\Role as EnumsRole;
use App\Models\Admin\Role;
use App\Models\Admin\RoleAction;
use App\Models\Admin\User;
use App\Models\Admin\UserRole;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserFilter extends Component
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
        $user_list = User::whereHas('roles', function($q) {
            $q->where('role_id', EnumsRole::CALL_CENTER->value);
        })->get();
        return view('components.complaint.user-filter', ['user_list' => $user_list]);
    }
}
