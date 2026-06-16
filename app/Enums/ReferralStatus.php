<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case PROCESSING = '0';
    case EARNED = '1';
}