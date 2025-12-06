<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RegisterOtpSMS extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * User object
     */
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['sms']; // Need to look on this...
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable)
    {
        return [
            'message' => "Dear, {$this->user->first_name}, Welcome to MeghaGas.",
            'phone' => $this->user->mobile,
        ];
    }
}
