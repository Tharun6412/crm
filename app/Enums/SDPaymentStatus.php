<?php

namespace App\Enums;

enum SDPaymentStatus: int
{
    case PAID = 1;
    case NOT_PAID = 2;
    case REVERSAL = 3;
}