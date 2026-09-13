<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateGeneratedNotification extends Notification
{
    use Queueable;

    protected $certificate;

    /**
     * Create a new notification instance.
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
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
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'certificate',
            'title' => 'Certificate Earned',
            'message' => 'Your certificate for '.$this->certificate->course->title.' is ready!',
            'url' => route('trainee.certificates.show', $this->certificate->id),
        ];
    }
}
