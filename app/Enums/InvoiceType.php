<?php

namespace App\Enums;

enum InvoiceType: int
{
    case GAS_BILL = 1;
    case SERVICE_INVOICE = 2;
    case LATE_PAYMENT_CHARGES = 3;
    case RENTAL_CHARGES = 4;
    case SD_EMI = 5;
    case CUSTOM_INVOICE = 6;
    case GEYSER_CONNECTION = 7;
}