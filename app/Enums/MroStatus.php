<?php

namespace App\Enums;

enum MroStatus: int 
{
    case REQUESTED = 1;
    case REQUEST_ACK_FAIL = 2;
    case RECEIVED = 3;
    case PROCESS_FAIL = 4;
    case BILL_SENT = 5;
    case CANCEL = 6;
}