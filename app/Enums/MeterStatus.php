<?php

namespace App\Enums;

enum MeterStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case REPLACE = 3;
}