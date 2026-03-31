<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintCloseOtpSmsNotification extends Notification
{
    /**
     * Consumer object
     */
    protected $params;

    /**
     * Create a new notification instance.
     */
    public function __construct($params)
    {
        // Assign
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Via SMS channel
        return ['sms'];
    }

     /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable)
    {
        return [
            'message' => "Your complaint closure OTP is " . $this->params['otp'] . ". Thank you for your cooperation. -MeghaGas",
        ];
    }
}
