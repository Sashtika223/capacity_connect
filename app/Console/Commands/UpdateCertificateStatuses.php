<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCertificateStatuses extends Command
{
    protected $signature = 'certificates:update-statuses';

    protected $description = 'Recalculate all certificate statuses and send expiry notifications.';

    public function handle(): void
    {
        $certs = Certificate::with('user')->get();
        $updated = 0;
        $notified = 0;

        foreach ($certs as $cert) {
            if (! $cert->expiry_date) {
                continue;
            }

            $oldStatus = $cert->cert_status;
            // recalculateStatus() is also called in booted(), but calling explicitly here too
            $cert->recalculateStatus();

            if ($cert->isDirty('cert_status')) {
                $cert->saveQuietly();
                $updated++;
            }

            // --- Notification logic ---
            $daysRemaining = Carbon::now()->diffInDays($cert->expiry_date, false);
            $thresholds = [90, 30, 7];
            $shouldNotify = false;

            foreach ($thresholds as $threshold) {
                if ($daysRemaining <= $threshold && $daysRemaining >= 0) {
                    // Only notify once per threshold window
                    if (! $cert->last_notified_at || $cert->last_notified_at->diffInDays(now()) >= 1) {
                        $shouldNotify = true;
                        break;
                    }
                }
            }

            if ($daysRemaining < 0 && (! $cert->last_notified_at || $cert->last_notified_at->diffInDays(now()) >= 7)) {
                $shouldNotify = true; // Expired — notify weekly
            }

            if ($shouldNotify && $cert->user) {
                $this->sendNotification($cert, $daysRemaining);
                $cert->update(['last_notified_at' => now()]);
                $notified++;
            }
        }

        $this->info("Certificate statuses updated: {$updated}. Notifications sent: {$notified}.");
    }

    protected function sendNotification(Certificate $cert, int $daysRemaining): void
    {
        $certName = $cert->certificate_name ?? $cert->course?->title ?? "Certificate #{$cert->certificate_id}";

        if ($daysRemaining < 0) {
            $message = "Your certificate '{$certName}' has EXPIRED. Please renew it immediately.";
        } elseif ($daysRemaining <= 7) {
            $message = "URGENT: Your certificate '{$certName}' expires in {$daysRemaining} days. Renewal Required.";
        } elseif ($daysRemaining <= 30) {
            $message = "Your certificate '{$certName}' expires in {$daysRemaining} days. Please plan for renewal.";
        } else {
            $message = "Reminder: Your certificate '{$certName}' expires in {$daysRemaining} days.";
        }

        DB::table('notifications')->insert([
            'user_id' => $cert->user_id,
            'type' => 'certificate_expiry',
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
