<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PdSmsNotification extends Notification implements ShouldQueue
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
            'message' => "Dear Meghagas consumer your account " . $this->consumer->crn . " has been permanently disconnected successfully.",
            'phone' => $this->consumer->mobile,
        ];
    }
}
