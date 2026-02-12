<?php

namespace App\Enums;

enum TransactionStatus: int
{
    case INITIATED = 1;
    case SUCCESS = 2;
    case FAIL = 3;
    case CANCEL = 4;
}