<?php
/**
 * Authentication helpers
 */

use Carbon\Carbon;
use Illuminate\Support\Number;

/**
 * Super admin chekcing
 */
if(!function_exists('isSuperAdmin')) {
    function isSuperAdmin() {
        if(in_array(1, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Admin checking
 */
if(!function_exists('isAdmin')) {
    function isAdmin() {
        if(in_array(2, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}