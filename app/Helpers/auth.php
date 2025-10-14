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
        if(session()->get('user')['role'] == 1 OR in_array(1, session()->get('user')['roles'])) {
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
        if(session()->get('user')['role'] == 2 OR in_array(2, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * CEO role, A custom role ondemand
 */
if(!function_exists('isCeo')) {
    function isCeo() {
        if(session()->get('user')['role'] == 3) {
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