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
    case DD_PO = 7;
    case BG_LC = 8;
    case TDS = 9;
    case WALLET = 10;
    case BAD_DEBTS = 11;
    case SD_EMI = 12;
    case FROM_SD = 13;
    case TO_INVOICE = 14;
    case PINE_LABS_EMI = 15;
}