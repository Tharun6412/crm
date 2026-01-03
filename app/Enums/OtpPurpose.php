<?php

namespace App\Enums;

enum OtpPurpose: string
{
    case REGISTER = 'register';
    case PASSWORD_RESET = 'pwd_rest';
    case COMPLAINT_CLOSE = 'comp_close';
}