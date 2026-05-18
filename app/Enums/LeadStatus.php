<?php

namespace App\Enums;

enum LeadStatus : int
{
    case OPEN = 1;
    case CLOSE = 2;
    case CANCEL = 3;
    case CREATED = 4;
    case INTERESTED = 5;
    case FOLLOWUP = 6;
    case CONVERTED = 7;
    case NOT_INTERESTED = 8;
    case NO_RESPONSE = 9;
    case DUPLICATE_LEAD = 10;
}
