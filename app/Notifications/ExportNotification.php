<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $exportId;
    protected $file_name;
    public function __construct($exportId, $file_name = null)
    {
        //
        $this->exportId = $exportId;
        $this->file_name = $file_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        // Customize message
        return [
            'message' => 'Your export is completed',
            'export_id' => $this->exportId,
            'file_name' => $this->file_name,
        ];
    }
}
