<?php

namespace App\Enums;

enum ComplaintStatus: int
{
    case REGISTER = 1;
    case ASSIGN = 2;
    case IN_PROGRESS = 3;
    case INVESTIGATION = 4;
    case CLOSE = 5;
    case CANCEL = 6;
    case REOPEN = 7;
}