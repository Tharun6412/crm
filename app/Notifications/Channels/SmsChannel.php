<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send
     */
    public function send($notifiable, Notification $notification)
    {
        echo $phone =  $notifiable->mobile;
        // $message = $notification->toSms($notifiable);
        
        // SMS API Call with Http
        // return 'Done';
    }
}
