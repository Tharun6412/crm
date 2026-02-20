<?php

namespace App\Enums;

enum SpotStatus: int
{
    case IN_PROGRESS = 1;
    case REQUEST_FOR_APPROVAL = 2;
    case APPROVED = 3;
    case CLOSED_WON = 4;
    case HOLD = 5;
    case CANCEL = 6;
    case CLOSED_LOST = 7;
    case REJECTED = 8;
}