<?php

namespace App\Enums;

enum ComplaintSegmentType: int
{
    case PNGDOM = 1;
    case PNGCOM = 2;
    case PNGIND = 3;
    case CNG = 4;
    case GENERAL = 5;
}