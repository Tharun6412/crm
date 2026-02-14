<?php

namespace App\Enums;

enum PaymentType: int
{
    case CASH_PAYMENT = 1;
    case ONLINE = 2;
    case CHEQUE = 3;
    case BANK_TRANSFER = 4;
    case CARD_PAYMENT = 5;
    case UPI = 6;
}