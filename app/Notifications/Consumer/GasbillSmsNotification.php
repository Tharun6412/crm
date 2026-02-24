<?php

namespace App\Notifications\Consumer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GasbillSmsNotification extends Notification
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
            'message' => "Your bill for CRN " . $this->consumer->crn . " of Rs. " . $this->consumer->total_price . " against usage of " . $this->consumer->invoice->total_reading . " SCM is generated vide bill no " . $this->consumer->invoice->invoice_no . " on " . date('d-m-y') . ". Due date:" . date('d-m-y',strtotime($this->consumer->invoice->due_date)) . ". Please note that any delay in payment post due date, late payment charges @2% per month shall be levied. Please pay online https://consumer.meghagas.com/quickBillPay MEGHAGAS.",
            'phone' => $this->consumer->mobile,
        ];
    }
}
