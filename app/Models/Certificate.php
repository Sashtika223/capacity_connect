<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_id', 'user_id', 'course_id', 'score', 'issue_date',
        'certificate_name', 'issuing_organization', 'expiry_date',
        'cert_status', 'renewal_file_path', 'renewal_verified', 'last_notified_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'last_notified_at' => 'datetime',
        'renewal_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Automatically compute status from expiry date.
     */
    public function recalculateStatus(): void
    {
        if (! $this->expiry_date) {
            $this->cert_status = 'valid';

            return;
        }

        $now = Carbon::now();
        $days = $now->diffInDays($this->expiry_date, false); // negative if past

        if ($days < 0) {
            $this->cert_status = 'expired';
        } elseif ($days <= 7) {
            $this->cert_status = 'renewal_required';
        } elseif ($days <= 30) {
            $this->cert_status = 'expiring_soon';
        } elseif ($days <= 90) {
            $this->cert_status = 'expiring_soon';
        } else {
            $this->cert_status = 'valid';
        }
    }

    /**
     * Friendly status label for display.
     */
    public function statusLabel(): string
    {
        return match ($this->cert_status) {
            'valid' => 'Valid',
            'expiring_soon' => 'Expiring Soon',
            'expired' => 'Expired',
            'renewal_required' => 'Renewal Required',
            default => 'Unknown',
        };
    }

    /**
     * Bootstrap: auto-recalculate status on save.
     */
    protected static function booted()
    {
        static::saving(function (Certificate $cert) {
            $cert->recalculateStatus();
        });
    }
}
