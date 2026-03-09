<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GasbillSmsNotification extends Notification implements ShouldQueue
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
            'message' => "Your bill for CRN " . $this->params['crn'] . " of Rs. " . $this->params['total_price'] . " against usage of " . $this->params['total_reading'] . " SCM is generated vide bill no " . $this->params['invoice_no'] . " on " . date('d-m-y') . ". Due date:" . date('d-m-y',strtotime($this->params['due_date'])) . ". Please note that any delay in payment post due date, late payment charges @2% per month shall be levied. Please pay online https://consumer.meghagas.com/quickBillPay MEGHAGAS.",
        ];
    }
}
