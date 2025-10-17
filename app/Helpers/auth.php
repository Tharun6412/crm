<?php
/**
 * Authentication helpers
 */

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

/**
 * Number format
 */
if(!function_exists('numberFormat')) {
    function numberFormat($number, $precision = 0) {
        return Number::format($number, precision: $precision, locale: 'en_IN');
    }
}