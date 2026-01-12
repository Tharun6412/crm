<?php

namespace App\Enums;

enum DocumentType: int
{
    case METER_IMAGE = 24;
    case ISOMETRIC_IMAGE = 25;
    case HSC_IMAGE = 26;
    case ACTIVATION_IMAGE = 27;
    case BILL_IMAGE = 28;
}