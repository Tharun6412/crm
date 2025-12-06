<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send email instantly
     */
    public static function send($recipient, Mailable $mailable)
    {
        Mail::to($recipient)->send($mailable);
    }

    /**
     * Dispatch email, will be queued and send later
     */
    public static function dispatch($recipient, Mailable $mailable)
    {
        Mail::to($recipient)->queue($mailable);
    }
}