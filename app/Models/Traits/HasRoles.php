<?php

namespace App\Models\Traits;

trait HasRoles
{
    /**
     * Super Admin
     */
    public function isSuperAdmin()
    {
        $user_roles = $this->roles()->pluck('role_id')->toArray();
        
        if(in_array(1, $user_roles)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Admin
     */
    public function isAdmin()
    {
        $user_roles = $this->roles()->pluck('role_id')->toArray();

        if(in_array(2, $user_roles)) {
            return true;
        }
        else {
            return false;
        }
    }
}