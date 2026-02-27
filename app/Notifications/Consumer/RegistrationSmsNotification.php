<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationSmsNotification extends Notification
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
            'message' => "Welcome to Megha City Gas! Thank you for registering with us. Your temporary registration number is {#var#} Click on the link below to pay your deposit online {#var#}",
            'phone' => $this->consumer->mobile,
        ];
    }
}
