<?php

namespace App\Enums;

/**
 * Interest rates mentioned as per the approved data
 * Compound interest rates
 */
enum EdiInterest: string
{
    case YEAR = "9.80";
    case MONTH = "0.78";
    case DATY = "0.256";
}