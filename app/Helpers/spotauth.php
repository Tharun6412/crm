<?php
/**
 * Spot Action Helper
 */

use App\Enums\SpotStatus;

/**
 * Super admin chekcing
 */
if(!function_exists('isSpotAdmin')) {
    function isSpotAdmin() {
        if(in_array(1, session()->get('user')['roles'])) {
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
        if(in_array(6, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}

/**
 * Only Viewer
 */
if(!function_exists('isViewer')) {
    function isViewer() {
        if(in_array(7, session()->get('user')['roles'])) {
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
if(!function_exists('isSpotClusterHead')) {
    function isSpotClusterHead() {
        if(in_array(3, session()->get('user')['roles'])) {
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
if(!function_exists('isSpotGaHead')) {
    function isSpotGaHead() {
        if(in_array(4, session()->get('user')['roles'])) {
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
if(!function_exists('isSpotSalesOfficer')) {
    function isSpotSalesOfficer() {
        if(in_array(5, session()->get('user')['roles'])) {
            return true;
        }
        else {
            return false;
        }
    }
}
/**
 * Stage and subStage or Status Checking Condition
 */
if(!function_exists('checkProspectHold')) {
    function checkProspectHold($status_id = null) {
        if($status_id != SpotStatus::HOLD->value OR $status_id != SpotStatus::CLOSED_WON->value) {
            return true;
        }
        return false;
    }
}

if(!function_exists('isInProgress')) {
    function isInProgress($status_id = null) {
        if($status_id == SpotStatus::IN_PROGRESS->value) {
            return true;
        }
        return false;
    }
}

if(!function_exists('isRequestForApproval')) {
    function isRequestForApproval($status_id = null) {
        if($status_id == SpotStatus::REQUEST_FOR_APPROVAL->value) {
            return true;
        }
        return false;
    }
}
?>