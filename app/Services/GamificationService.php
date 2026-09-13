<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    /**
     * Award points to a user and check for level ups and badges.
     */
    public function awardPoints(User $user, int $points, string $reason = '')
    {
        $user->points = ($user->points ?? 0) + $points;

        // Calculate Level (Level 1: 0-99, Level 2: 100-249, Level 3: 250-499, Level 4: 500-999, Level 5: 1000+)
        $oldLevel = $user->level ?? 1;
        $newLevel = 1;
        if ($user->points >= 1000) {
            $newLevel = 5;
        } elseif ($user->points >= 500) {
            $newLevel = 4;
        } elseif ($user->points >= 250) {
            $newLevel = 3;
        } elseif ($user->points >= 100) {
            $newLevel = 2;
        }

        $user->level = $newLevel;
        $user->save();

        $this->checkBadges($user);
    }

    /**
     * Maintain user daily learning streak.
     */
    public function updateStreak(User $user)
    {
        $lastActive = $user->last_active_at;
        $now = now();

        if (! $lastActive) {
            $user->learning_streak = 1;
        } else {
            $lastActiveCarbon = $lastActive instanceof Carbon ? $lastActive : Carbon::parse($lastActive);
            $diffInDays = (int) $lastActiveCarbon->diffInDays($now);
            if ($diffInDays == 1) {
                $user->learning_streak = ($user->learning_streak ?? 1) + 1;
            } elseif ($diffInDays > 1) {
                $user->learning_streak = 1;
            }
        }

        $user->last_active_at = $now;
        $user->save();
    }

    /**
     * Check if user qualifies for default badges.
     */
    public function checkBadges(User $user)
    {
        // Ensure default badges exist in database
        $defaultBadges = [
            ['slug' => 'first-step', 'name' => 'First Step', 'description' => 'Completed your first course or assessment', 'points_required' => 50, 'icon' => 'bi-flag'],
            ['slug' => 'scholar', 'name' => 'Capacity Scholar', 'description' => 'Earned over 250 learning points', 'points_required' => 250, 'icon' => 'bi-mortarboard'],
            ['slug' => 'master', 'name' => 'Domain Master', 'description' => 'Earned over 500 learning points', 'points_required' => 500, 'icon' => 'bi-trophy'],
        ];

        foreach ($defaultBadges as $badgeData) {
            $badge = Badge::firstOrCreate(['slug' => $badgeData['slug']], $badgeData);
            if ($user->points >= $badge->points_required) {
                if (! $user->badges()->where('badge_id', $badge->id)->exists()) {
                    $user->badges()->attach($badge->id, ['awarded_at' => now()]);
                }
            }
        }
    }
}
