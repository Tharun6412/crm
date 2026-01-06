<?php

namespace App\Enums;

enum MeterStatus: int
{
    case Active = 1;
    case InActive = 2;
    case Replace = 3;
}