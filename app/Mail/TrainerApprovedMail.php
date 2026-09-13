<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerApprovedMail extends Mailable
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
            subject: 'Trainer Registration Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer_approved',
        );
    }
}
