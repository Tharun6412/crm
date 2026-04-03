<?php
/**
 * Authentication helpers
 */

use Carbon\Carbon;
use Illuminate\Support\Number;
use App\Enums\SpotStatus;
use App\Enums\Role;
use App\Enums\SpotStages;

/**
 * Super admin chekcing
 */
if(!function_exists('isSuperAdmin')) {
    function isSuperAdmin() {
        if(in_array(Role::SUPER_ADMIN->value, session()->get('user')['roles'])) {
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
        if(in_array(Role::ADMIN->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Full Access
 */
if(!function_exists('isFullAccess')) {
    function isFullAccess() {
        if(in_array(Role::FULL_ACCESS->value, session()->get('user')['roles'])) {
            return true;
        }else {
            return false;
        }
    }
}

/**
 * Admin chekcing
 */
if(!function_exists('isApiAdmin')) {
    function isApiAdmin() {
        if(in_array(Role::ADMIN->value, request()->user()->roles()->pluck('adm_roles.id')->toArray())) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Super Admin chekcing
 */
if(!function_exists('isApiSuperAdmin')) {
    function isApiSuperAdmin() {
        if(in_array(Role::SUPER_ADMIN->value, request()->user()->roles()->pluck('adm_roles.id')->toArray())) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * FULL Acces chekcing
 */
if(!function_exists('isApiFullAccess')) {
    function isApiFullAccess() {
        if(in_array(Role::FULL_ACCESS->value, request()->user()->roles()->pluck('adm_roles.id')->toArray())) {
            return true;
        }
        else {
            return false;
        }
    }
}
/**
 * Head Office Sales
 */
if(!function_exists('isHOSales')) {
    function isHOSales() {
        if(in_array(Role::HO_SALES->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Only Viewer for Spot Application
 */
if(!function_exists('isViewer')) {
    function isViewer() {
        if(in_array(Role::VIEWER->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Cluster Head checking
 */
if(!function_exists('isClusterHead')) {
    function isClusterHead() {
        if(in_array(Role::CLUSTER_HEAD->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}
/**
 * Ga Head checking
 */
if(!function_exists('isGaHead')) {
    function isGaHead() {
        if(in_array(Role::GA_HEAD->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Sales Officer checking
 */
if(!function_exists('isSalesOfficer')) {
    function isSalesOfficer() {
        if(in_array(Role::SALES_OFFICER->value, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}


/**
 * Spot Related Conditions
 * @var int $status_id
 * Status Checking Conditions
 */

/**
 * Check the prospect in Hold or Not
 */
if(!function_exists('checkProspectHold')) {
    function checkProspectHold($status_id = null) {
        if($status_id != SpotStatus::HOLD->value OR $status_id != SpotStatus::CLOSED_WON->value) {
            return true;
        }
        return false;
    }
}

/**
 * Check the user is in Progress
 */
if(!function_exists('isInProgress')) {
    function isInProgress($status_id = null) {
        if($status_id == SpotStatus::IN_PROGRESS->value) {
            return true;
        }
        return false;
    }
}

/**
 * Check if it is request for approval
 */
if(!function_exists('isRequestForApproval')) {
    function isRequestForApproval($status_id = null) {
        if($status_id == SpotStatus::REQUEST_FOR_APPROVAL->value) {
            return true;
        }
        return false;
    }
}

/**
 * Check if it is approved
 */
if(!function_exists('isApproved')) {
    function isApproved($status_id = null) {
        if($status_id == SpotStatus::APPROVED->value) {
            return true;
        }
        return false;
    }
}

/**
 * Check the prospect is Closed Won
 * @param $stage_id
 */
if(!function_exists('isClosedWon')) {
    function isClosedWon($stage_id = null) {
        if($stage_id == SpotStages::CLOSE->value) {
            return true;
        }
        return false;
    }
}

/**
 * Check the Pipeline Availability
 * @param $status_id
 * @param $pa => PipeLine Availability
 */
if(!function_exists('isPipeLineAvailable')) {
    function isPipeLineAvailable($pa, $status_id = NULL) {
        if($pa == 2 AND ($status_id == SpotStatus::REQUEST_FOR_APPROVAL->value OR $status_id == SpotStatus::APPROVED->value OR $status_id == SpotStatus::IN_PROGRESS->value)) {
            return true;
        }
        return false;
    }
}