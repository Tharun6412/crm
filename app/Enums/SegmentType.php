<?php

namespace App\Enums;

enum SegmentType: int
{
    case DOMESTIC = 1;
    case COMMERCIAL = 2;
    case INDUSTRIAL = 3;
    case CNG = 4;
}