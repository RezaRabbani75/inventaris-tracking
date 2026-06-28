<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotificationRequest extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $action_url;
    protected $action_text;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $action_url = null, $action_text = null, $type = 'info')
    {
        $this->title = $title;
        $this->message = $message;
        $this->action_url = $action_url;
        $this->action_text = $action_text;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Menggunakan channel database agar tersimpan di tabel notifications
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->action_url,
            'action_text' => $this->action_text ?? 'Lihat',
            'type' => $this->type,
        ];
    }
}