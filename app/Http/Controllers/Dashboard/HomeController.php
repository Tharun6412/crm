<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Mail\User\RegisterOtpMail;
use App\Models\Consumer\Consumer;
use App\Models\Admin\User;
use App\Notifications\RegisterOtpSMS;
use App\Services\EmailService;
use App\Services\SmsService;

class HomeController extends Controller
{
    /**
     * Index
     */

    public function index()
    {
        // Sample Email
        // $consumer = Consumer::find(1);
        $user = User::find(1);
        // EmailService::dispatch($user, new RegisterOtpMail($user));
        
        // SmsService::send($user, new RegisterOtpSMS($user));

        return view('dashboard.home');
    }
}