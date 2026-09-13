<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class HttpMailService
{
    /**
     * Send a mailable via HTTPS API (Port 443) or SMTP (with fast reachability check).
     */
    public static function send(string $toEmail, Mailable $mailable, ?string $toName = null): bool
    {
        $subject = $mailable->subject ?? 'Capacity Connect Notification';
        $htmlContent = $mailable->render();
        $senderEmail = config('mail.from.address', 'admin.capacity.connect.lms@gmail.com');
        $senderName = config('mail.from.name', 'Capacity Connect');

        // 1. Try Brevo HTTPS API (Port 443) if key is present
        $brevoApiKey = config('services.brevo.key') ?: env('BREVO_API_KEY');
        if (! empty($brevoApiKey)) {
            try {
                $response = Http::withHeaders([
                    'api-key' => $brevoApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(5)->post('https://api.brevo.com/v3/smtp/email', [
                    'sender' => ['name' => $senderName, 'email' => $senderEmail],
                    'to' => [['email' => $toEmail, 'name' => $toName ?? $toEmail]],
                    'subject' => $subject,
                    'htmlContent' => $htmlContent,
                ]);

                if ($response->successful()) {
                    Log::info("Email successfully delivered via Brevo HTTPS API to {$toEmail}");

                    return true;
                } else {
                    Log::error('Brevo HTTPS API Error: '.$response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Brevo HTTPS API Exception: '.$e->getMessage());
            }
        }

        // 2. Try Resend HTTPS API (Port 443) if key is present
        $resendApiKey = env('RESEND_API_KEY');
        if (! empty($resendApiKey)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$resendApiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(5)->post('https://api.resend.com/emails', [
                    'from' => "{$senderName} <onboarding@resend.dev>",
                    'to' => [$toEmail],
                    'subject' => $subject,
                    'html' => $htmlContent,
                ]);

                if ($response->successful()) {
                    Log::info("Email successfully delivered via Resend HTTPS API to {$toEmail}");

                    return true;
                } else {
                    Log::error('Resend HTTPS API Error: '.$response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Resend HTTPS API Exception: '.$e->getMessage());
            }
        }

        // 3. Fast reachability check for SMTP port before sending via SMTP
        $host = config('mail.mailers.smtp.host', 'smtp.gmail.com');
        $port = (int) config('mail.mailers.smtp.port', 587);
        $password = config('mail.mailers.smtp.password');

        $isSmtpReachable = false;
        if (! empty($password)) {
            $socket = @fsockopen($host, $port, $errno, $errstr, 2);
            if ($socket) {
                $isSmtpReachable = true;
                fclose($socket);
            }
        }

        if ($isSmtpReachable) {
            try {
                Mail::to($toEmail)->send($mailable);
                Log::info("Email successfully delivered via SMTP to {$toEmail}");

                return true;
            } catch (\Throwable $e) {
                Log::error("SMTP send failed for {$toEmail}: ".$e->getMessage());
            }
        }

        // 4. Clean fallback to Log mailer
        try {
            Mail::mailer('log')->to($toEmail)->send($mailable);
            Log::info("Email logged to storage/logs/laravel.log for {$toEmail}");

            return true;
        } catch (\Throwable $e) {
            Log::error('Log mailer failed: '.$e->getMessage());

            return false;
        }
    }
}
