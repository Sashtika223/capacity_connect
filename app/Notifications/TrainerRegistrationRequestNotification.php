<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TrainerRegistrationRequestNotification extends Notification
{
    use Queueable;

    public $trainer;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $trainer)
    {
        $this->trainer = $trainer;
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
            'type' => 'trainer_registration_request',
            'title' => 'New Trainer Registration Request',
            'message' => 'New trainer registration request from '.$this->trainer->name.' ('.$this->trainer->email.'). Action required.',
            'trainer_id' => $this->trainer->id,
            'trainer_name' => $this->trainer->name,
            'trainer_email' => $this->trainer->email,
            'url' => route('admin.trainer-requests.index'),
            'registered_at' => now()->toIso8601String(),
        ];
    }
}
