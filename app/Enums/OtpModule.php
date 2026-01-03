<?php

namespace App\Enums;

enum OtpModule: string
{
    case USER = 'user';
    case CONSUMER = 'consumer';
}