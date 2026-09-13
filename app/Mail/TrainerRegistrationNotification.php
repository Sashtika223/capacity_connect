<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $trainer;

    public function __construct(User $trainer)
    {
        $this->trainer = $trainer;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Trainer Registration Request - '.$this->trainer->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer_registration_notification',
        );
    }
}
