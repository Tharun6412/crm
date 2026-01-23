<?php

namespace App\Enums;

enum ModuleType: int
{
    case PAY_DEPOSIT = 1;
    case SECURITY_DEPOSIT = 2;
    case GAS_BILL = 3;
    case INVOICE = 4;
    case RECHARGE = 5;
}