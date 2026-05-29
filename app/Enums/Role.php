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
    case FULL_ACCESS = 8;
    case EMPLOYEE = 9;
    case CALL_CENTER = 10;
    case MDPE = 13;
    case STEEL = 14;
    case GI_ENGINEER = 15;
    case HSE = 16;
    case ACTIVATION = 17;
    case TICKET_APPROVAL = 18;
    case FINANCE = 19;
}