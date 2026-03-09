<?php

namespace App\Enums;

enum DocumentType: int
{
    case AADHAR = 1;
    case METER_IMAGE = 24;
    case ISOMETRIC_IMAGE = 25;
    case HSC_IMAGE = 26;
    case ACTIVATION_IMAGE = 27;
    case BILL_IMAGE = 28;
    case LOAD_ASSESSMENT_SHEET = 29;
    case OFFER = 30;
    case GSA = 31;
}