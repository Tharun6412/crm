<?php

namespace App\Enums;

enum SpotStages: int
{
    case SUSPECT = 1;
    case PROSPECT = 2;
    case APPROACH = 3;
    case NEGOTIATE = 4;
    case CLOSE = 5;
    case ORDER = 6;
    case RESEARCH = 7;
    case INITIAL_REACH_OUT = 8;
    case QUALIFY_NEEDS = 9;
    case EVALUATE_FIT = 10;
    case TECHNICAL = 11;
    case OFFER = 12;
    case GSA = 13;
    case PRICING = 14;
    case KNOWLEDGE_PARTNER = 15;
    case WIN = 16;
    case LOSE = 17;
    case EXECUTION = 18;
    case COMMISSION = 19;
}