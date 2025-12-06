<?php

namespace App\Services;

use Illuminate\Notifications\Notification;

class SmsService
{
    /**
     * Send instantly
     */
    public static function send($recipient, Notification $notification)
    {
        $recipient->notifyNow($notification);
    }

    /**
     * Dispatch, send in delay
     */
    public static function dispatch($recipient, Notification $notification)
    {
        $recipient->notify($notification);
    }
}