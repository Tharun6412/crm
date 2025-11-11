<?php
/**
 * Spot Action Helper
 */


/**
 * Super admin chekcing
 */
if(!function_exists('isSpotAdmin')) {
    function isSpotAdmin() {
        if(in_array(1, session()->get('user')['spot_roles'])) {
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
        if(in_array(5, session()->get('user')['spot_roles'])) {
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
        if(in_array(6, session()->get('user')['spot_roles'])) {
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
        if(in_array(2, session()->get('user')['spot_roles'])) {
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
        if(in_array(3, session()->get('user')['spot_roles'])) {
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
        if(in_array(4, session()->get('user')['spot_roles'])) {
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
        if($status_id != 35 OR $status_id != 34) {
            return true;
        }
        return false;
    }
}

if(!function_exists('isInProgress')) {
    function isInProgress($status_id = null) {
        if($status_id == 31) {
            return true;
        }
        return false;
    }
}

if(!function_exists('isRequestForApproval')) {
    function isRequestForApproval($status_id = null) {
        if($status_id == 32) {
            return true;
        }
        return false;
    }
}
?>