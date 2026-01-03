<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\OtpPurpose;
use App\Http\Controllers\Controller;
use App\Mail\User\RegisterOtpMail;
use App\Models\Consumer\Consumer;
use App\Models\Admin\User;
use App\Notifications\RegisterOtpSMS;
use App\Services\EmailService;
use App\Services\OtpService;
use App\Services\SmsService;

class HomeController extends Controller
{
    /**
     * Index
     * 
     * Dashboard
     */
    public function index()
    {
        // Sample Email
        // $consumer = Consumer::find(1);
        // $user = User::find(1);
        // EmailService::dispatch($user, new RegisterOtpMail($user));
        
        // SmsService::send($user, new RegisterOtpSMS($user));
        
        // Test OTP
        // echo $otp = OtpService::create('9703722588', 'registration', 'user');
        // 519048, 295661, 272222, 230066
        // if(OtpService::verify('9703722588', 'registration', '230066', 'user'))
        //     echo 'YEs';
        // else
        //     echo 'No';
        // echo OtpPurpose::REGISTER->value;

        return view('dashboard.home');
    }
}