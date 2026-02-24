<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintRegisterSmsNotification extends Notification
{
    use Queueable;

    /**
     * Consumer object
     */
    protected $consumer;

    /**
     * Create a new notification instance.
     */
    public function __construct($consumer)
    {
        // Assign
        $this->consumer = $consumer;
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
            'message' => "Dear Customer, Your complaint no " . $this->consumer->complaint_no . ". is registered. We expect to attend it within 24 hours. Download Meghagas app https://bit.ly/3zPGMG3 & stay connected - Thanks, Megha Gas",
            'phone' => $this->consumer->mobile,
        ];
    }
}
