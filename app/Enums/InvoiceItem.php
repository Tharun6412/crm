<?php

namespace App\Enums;

enum InvoiceItem: int
{
    case DOMESTIC_REGISTRATION = 16;
    case DLPC = 41;
    case CLPC = 42;
    case ILPC = 45;
    case SDEMI = 44;
    case RENTAL_CHARGES = 43;
    case SERVICE_INVOICE = 38;
    case DISCONNECTION_CHARGES = 33;
    case COMMERCIAL_REGISTRATION = 39;
    case PDISCONNECT = 19;
}