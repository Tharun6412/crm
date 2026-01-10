<?php

namespace App\Enums;

enum PaymentMode: string
{
    case TEST = 'test';
    case PRODUCTION = 'production';
}
