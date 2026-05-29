<?php

namespace App\Enums;

enum TicketStatus: Int
{
    case REGISTER = 1;
    case APPROVE = 2;
    case PROCESSING = 3;
    case HOLD = 4;
    case CLOSE = 5;
    case CANCEL = 6;

}
