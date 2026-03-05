<?php

namespace App\Enums;

enum AwsPath: string
{
    case REGISTRATION = "registration";
    case EXECUTION = "execution";
    case HSC = "hsc";
    case ACTIVATION = "activation";
    case BILLS = "bills";
    case COMPLAINTS = "complaints";
    case METER_CHANGE = "meter_change";
    case SPOT = "spot";
}