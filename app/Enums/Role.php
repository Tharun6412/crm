<?php

namespace App\Enums;

enum Role: int
{
    case ADMIN = 1;
    case SUPER_ADMIN = 2;
    case CLUSTER_HEAD = 3;
    case GA_HEAD = 4;
    case SALES_OFFICER = 5;
    case HO_SALES = 6;
    case VIEWER = 7;
}