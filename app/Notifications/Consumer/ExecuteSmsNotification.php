<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExecuteSmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            'message' => "Dear MeghaGas consumer your account " . $this->params['crn'] . " has been executed successfully. Please pay the Security deposit balance. Please ignore if already paid",
        ];
    }
}
