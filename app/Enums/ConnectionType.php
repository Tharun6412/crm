<?php

namespace App\Enums;

enum ConnectionType: int
{
    case POSTPAID = 1;
    case PREPAID = 2;
}