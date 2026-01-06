<?php

namespace App\Enums;
/**
 * Constants for Refund Status
 */
enum RefundStatus: int
{
    case REQUEST = 1;
    case PROCESS = 2;
    case APPROVE = 3;
    case CLOSE = 4;
}