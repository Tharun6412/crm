<?php

namespace App\Enums;

enum ComplaintType: int
{
    case ENQUIRY = 1;
    case REQUEST = 2;
    case COMPLAINT = 3;
    case REFUND = 4;
}