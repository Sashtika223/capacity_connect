<?php

namespace App\Console\Commands;

use App\Mail\TrainerRegistrationNotification;
use App\Models\User;
use App\Services\HttpMailService;
use Illuminate\Console\Command;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {recipient : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email using HttpMailService (Brevo API) to a specified email address';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = $this->argument('recipient');

        $this->info('Verifying configuration...');
        $apiKey = config('services.brevo.key') ?: env('BREVO_API_KEY');

        if (empty($apiKey)) {
            $this->error('ERROR: BREVO_API_KEY is not set in .env or config/services.php!');

            return Command::FAILURE;
        }

        $senderEmail = config('mail.from.address');
        $senderName = config('mail.from.name');

        $this->line(' - BREVO_API_KEY: '.substr($apiKey, 0, 8).'...'.substr($apiKey, -4));
        $this->line(" - Sender Address: {$senderEmail}");
        $this->line(" - Sender Name: {$senderName}");
        $this->line(" - Recipient: {$recipient}");

        $dummyTrainer = new User([
            'name' => 'Test Recipient',
            'email' => $recipient,
        ]);

        $mailable = new TrainerRegistrationNotification($dummyTrainer);

        $this->info('Sending test email via HttpMailService...');
        $success = HttpMailService::send($recipient, $mailable, 'Test Recipient');

        if ($success) {
            $this->info("SUCCESS: Test email dispatch completed! Please check inbox for {$recipient}.");

            return Command::SUCCESS;
        } else {
            $this->error("FAILED: Could not send test email to {$recipient}. Check storage/logs/laravel.log.");

            return Command::FAILURE;
        }
    }
}
