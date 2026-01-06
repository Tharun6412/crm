<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case COMPLETED = 1;
    case PROGRESS = 2;
    case REVERSAL = 3;
}