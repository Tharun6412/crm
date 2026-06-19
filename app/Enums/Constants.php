<?php

namespace App\Enums;

enum Constants: string
{
    case DPNG_LPC = '20';
    case CPNG_LPC = '50';
    case IPNG_LPC = '100';
    case DPNG_DUEDAYS = '15';
    case CORRECTION_FACTOR = '1';
    case REFERRAL_AMOUNT = '150';
    // case REFERRER_AMOUNT = self::REFERRAL_AMOUNT; // because of same value we used the self. Incase of different value, we can directly replace the value.

    public static function REFERRER_AMOUNT(): string
    {
        return self::REFERRAL_AMOUNT->value; // returns '150' directly
    }
}