<?php

namespace App\Enums;

enum ConsumerStatus: int
{
    case PRE_REGISTER = 1;
    case REGISTER = 2;
    case ACCEPT = 3;
    case EXECUTE = 4;
    case HSC = 5;
    case ACTIVATE = 6;
    case TD = 7;
    case PD = 8;
    case REJECT = 9;
    case RECONNECT = 10;
}