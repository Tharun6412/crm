<?php
/**
 * Utils Helper Functions
 */

use Carbon\Carbon;
use Illuminate\Support\Number;
/**
 * Number format
 */
if(!function_exists('numberFormat')) {
    function numberFormat($number, $precision = 0) {
        if(is_numeric($number)) {
            return Number::format($number, precision: $precision, locale: 'en_IN');
        }else {
            return 0;
        }
    }
}

/**
 * Date format
 */
if(!function_exists('dateFormat')) {
    function dateFormat($date, $time = 0) {
        if(!$date) {
            return null;
        }

        return Carbon::parse($date)->format(($time) ? 'd.m.Y H:i' : 'd.m.Y');
    }
}

/**
 * Mask Function
 */
if(!function_exists('maskNumber')) {
    function maskNumber($number) {
        if(is_null($number)) {
            return ''; 
        }else {
            return str_repeat('*', max(strlen($number)-4, 0)).substr($number,-4);
        }
    }
}
?>