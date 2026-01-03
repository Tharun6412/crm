<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case COMPLETED = 1;
    case PENDING = 2;
    case REVERSAL = 3;
}