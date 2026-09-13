<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLearningContentNotification extends Notification
{
    use Queueable;

    protected $course;

    protected $contentType;

    /**
     * Create a new notification instance.
     */
    public function __construct($course, $contentType = 'lesson')
    {
        $this->course = $course;
        $this->contentType = $contentType;
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
            'type' => 'content',
            'title' => 'New Learning Content',
            'message' => 'A new '.$this->contentType.' has been added to '.$this->course->title,
            'url' => route('trainee.courses.show', $this->course->id),
        ];
    }
}
