<?php

namespace App\Enums;

enum TaxType: int
{
    case VAT = 1;
    case GST = 2;
    case IGST = 3;
    case CST = 4;
    case EXCISE_DUTY = 5;
}