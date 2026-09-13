<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $trainer;

    public ?string $rejectionReason;

    public function __construct(User $trainer, ?string $rejectionReason = null)
    {
        $this->trainer = $trainer;
        $this->rejectionReason = $rejectionReason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trainer Registration Request Rejected',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer_rejected',
        );
    }
}
