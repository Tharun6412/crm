<?php

namespace App\Enums;

enum GeyserStatus : int
{
    case REGISTER = 1;
    case EXECUTE = 2;
    case ACTIVE = 3;
    case DISCONNECT = 4;
}
