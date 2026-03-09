<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GasbillPaymentSmsNotification extends Notification implements ShouldQueue
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
            'message' => "Dear MeghaGas consumer your payment Rs. " . $this->params['total_price'] . " towards your MeghaGas bill with invoice number " . $this->params['invoice_no'] . " has been received on " . date('d-m-Y'),
        ];
    }
}
