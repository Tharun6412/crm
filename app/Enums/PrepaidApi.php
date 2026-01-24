<?php
namespace App\Enums;

enum PrepaidApi: string
{
    // Dev and Test environment links
    case TEST_ACQUISITION = 'https://staging-api.gas.polarisgrids.com/hes/api/gas_mdms/gas/megha-gas-meter-customer-create/';
    case TEST_RECHARGE = 'https://staging-api.gas.polarisgrids.com/hes/api/ble/gas/recharge-web/';
    case TEST_CANCEL_RECHARGE = 'https://staging-api.gas.polarisgrids.com/hes/api/ble/gas/cancel-recharge/';
    case TEST_MRO_REQUEST = 'https://staging-api.gas.polarisgrids.com/hes/api/gas_mdms/gas/billing-mro-megha/';
    case TEST_MRO_ACKNOWLEDGMENT = 'https://staging-api.gas.polarisgrids.com/hes/api/gas_mdms/gas/mro-ack/';
    case TEST_PRICE_UPDATE = 'https://staging-api.gas.polarisgrids.com/hes/api/gas_mdms/gas/gas-price-updation/';
    case TEST_ON_DEMAND_READING = 'https://staging-api.gas.polarisgrids.com/hes/api/gas_mdms/gas/meter-consumption-data/';
    
    // Production environment links.
    case PROD_ACQUISITION = 'https://api.gas.polarisgrids.com/hes/api/gas_mdms/gas/megha-gas-meter-customer-create/';
    case PROD_RECHARGE = 'https://api.gas.polarisgrids.com/hes/api/ble/gas/recharge-web/';
    case PROD_CANCEL_RECHARGE = 'https://api.gas.polarisgrids.com/hes/api/ble/gas/cancel-recharge/';
    case PROD_MRO_REQUEST = 'https://api.gas.polarisgrids.com/hes/api/gas_mdms/gas/billing-mro-megha/';
    case PROD_MRO_ACKNOWLEDGMENT = 'https://api.gas.polarisgrids.com/hes/api/gas_mdms/gas/mro-ack/';
    case PROD_PRICE_UPDATE = 'https://api.gas.polarisgrids.com/hes/api/gas_mdms/gas/gas-price-updation/';
    case PROD_ON_DEMAND_READING = 'https://api.gas.polarisgrids.com/hes/api/gas_mdms/gas/meter-consumption-data/';

    /**
     * Get the Acquisition API.
     */
    public static function acquisition(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_ACQUISITION,
           default => self::TEST_ACQUISITION, 
        };
    }

    /**
     * Get the Recharge API.
     */
    public static function recharge(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_RECHARGE,
           default => self::TEST_RECHARGE, 
        };
    }

    /**
     * Get the cancel recharge API.
     */
    public static function cancelRecharge(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_CANCEL_RECHARGE,
           default => self::TEST_CANCEL_RECHARGE, 
        };
    }

    /**
     * Get the mro request API.
     */
    public static function mroRequest(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_MRO_REQUEST,
           default => self::TEST_MRO_REQUEST, 
        };
    }

    /**
     * Get the mro acknowledgement API.
     */
    public static function mroAcknowledgment(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_MRO_ACKNOWLEDGMENT,
           default => self::TEST_MRO_ACKNOWLEDGMENT, 
        };
    }

    /**
     * Get the price update API.
     */
    public static function priceUpdate(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_PRICE_UPDATE,
           default => self::TEST_PRICE_UPDATE, 
        };
    }

    /**
     * Get the on demand readings API.
     */
    public static function onDemandRead(): self
    {
        return match(config('app.env')) {
           'production' => self::PROD_ON_DEMAND_READING,
           default => self::TEST_ON_DEMAND_READING, 
        };
    }
}