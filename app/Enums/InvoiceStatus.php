<?php

namespace App\Enums;

enum InvoiceStatus: int
{
    case NOT_PAID = 1;
    case PARTIALLY_PAID = 2;
    case PAID = 3;
    case CANCEL = 4;
}