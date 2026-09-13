<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;

class ExpertDiscoveryController extends Controller
{
    /**
     * Calculate a holistic Expert Score (0–100) for a user
     * based on multiple signals from real DB data.
     * This is fully rule-based and transparent — no fake AI.
     */
    protected function calculateExpertScore(User $user): array
    {
        $score = 0;
        $evidence = [];
        $expertiseAreas = [];

        // --- SIGNAL 1: Competency Levels (Max 30 pts) ---
        $compScore = 0;
        foreach ($user->competencies as $comp) {
            $level = (int) $comp->pivot->current_level; // 1=Beginner…4=Expert
            $points = $level * 7; // Max 28 per competency but capped
            if ($level >= 3) {
                $levelLabel = $level === 4 ? 'Expert' : 'Advanced';
                $expertiseAreas[] = ['name' => $comp->name, 'level' => $levelLabel];
                $evidence[] = "Competency: {$comp->name} — {$levelLabel}";
            }
            $compScore += $points;
        }
        $compScore = min(30, $compScore);
        $score += $compScore;

        // --- SIGNAL 2: Assessment Performance (Max 25 pts) ---
        $assessScore = 0;
        $attempts = $user->assessmentAttempts ?? collect();
        if ($attempts->isNotEmpty()) {
            $avgPct = $attempts->avg('score_percentage');
            if ($avgPct >= 90) {
                $assessScore = 25;
                $evidence[] = "Assessment Performance: Avg {$avgPct}% (Excellent)";
            } elseif ($avgPct >= 75) {
                $assessScore = 18;
                $evidence[] = "Assessment Performance: Avg {$avgPct}% (Good)";
            } elseif ($avgPct >= 60) {
                $assessScore = 10;
                $evidence[] = "Assessment Performance: Avg {$avgPct}% (Satisfactory)";
            }
        }
        $score += $assessScore;

        // --- SIGNAL 3: Certifications (Max 25 pts) ---
        $certScore = 0;
        $certs = Certificate::where('user_id', $user->id)->with('course')->get();
        $certCount = $certs->count();
        if ($certCount >= 5) {
            $certScore = 25;
        } elseif ($certCount >= 3) {
            $certScore = 18;
        } elseif ($certCount >= 1) {
            $certScore = 10;
        }
        if ($certCount > 0) {
            $evidence[] = "Certifications: {$certCount} certificate(s) obtained";
        }
        $score += $certScore;

        // --- SIGNAL 4: Course Completion Rate (Max 20 pts) ---
        $courseScore = 0;
        $enrollments = $user->enrollments ?? collect();
        if ($enrollments->isNotEmpty()) {
            $completed = $enrollments->where('status', 'completed')->count();
            $total = $enrollments->count();
            $rate = ($completed / $total) * 100;
            if ($rate >= 90) {
                $courseScore = 20;
                $evidence[] = "Course Completion: {$completed}/{$total} courses — {$rate}% rate (Exceptional)";
            } elseif ($rate >= 70) {
                $courseScore = 14;
                $evidence[] = "Course Completion: {$completed}/{$total} courses — ".round($rate).'% rate (Strong)';
            } elseif ($rate >= 50) {
                $courseScore = 7;
                $evidence[] = "Course Completion: {$completed}/{$total} courses — ".round($rate).'% rate';
            }
        }
        $score += $courseScore;

        // Derive recommended role
        $recommendedRole = 'Subject Matter Expert';
        if ($score >= 80) {
            $recommendedRole = 'Lead Trainer / Knowledge Custodian';
        } elseif ($score >= 60) {
            $recommendedRole = 'Senior Trainer / Mentor';
        } elseif ($score >= 40) {
            $recommendedRole = 'Peer Mentor / Course Leader';
        }

        return [
            'score' => min(100, $score),
            'evidence' => $evidence,
            'expertise_areas' => $expertiseAreas,
            'recommended_role' => $recommendedRole,
            'breakdown' => [
                'competency' => $compScore,
                'assessments' => $assessScore,
                'certifications' => $certScore,
                'courses' => $courseScore,
            ],
        ];
    }

    /**
     * "Hidden Experts" page — proactively scores ALL users.
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $minScore = (int) $request->input('min_score', 0);

        $users = User::with(['competencies', 'assessmentAttempts', 'enrollments', 'certificates', 'traineeProfile', 'trainerProfile'])
            ->whereIn('role', ['trainee', 'trainer'])
            ->get();

        $results = [];
        foreach ($users as $user) {
            $scored = $this->calculateExpertScore($user);

            // Filter by keyword if provided
            if ($query) {
                $keyword = strtolower($query);
                $matchesKeyword = false;

                if (str_contains(strtolower($user->name), $keyword) ||
                    str_contains(strtolower($user->department ?? ''), $keyword) ||
                    str_contains(strtolower($user->designation ?? ''), $keyword) ||
                    str_contains(strtolower($user->skills ?? ''), $keyword) ||
                    str_contains(strtolower($user->qualifications ?? ''), $keyword) ||
                    str_contains(strtolower($user->work_experience ?? ''), $keyword)) {
                    $matchesKeyword = true;
                }

                if (! $matchesKeyword) {
                    foreach ($scored['expertise_areas'] as $area) {
                        if (str_contains(strtolower($area['name']), $keyword)) {
                            $matchesKeyword = true;
                            break;
                        }
                    }
                }

                if (! $matchesKeyword) {
                    foreach ($scored['evidence'] as $e) {
                        if (str_contains(strtolower($e), $keyword)) {
                            $matchesKeyword = true;
                            break;
                        }
                    }
                }

                if (! $matchesKeyword) {
                    continue;
                }
            }

            // Filter by minimum score
            if ($scored['score'] < $minScore) {
                continue;
            }

            // Only show users with meaningful expertise signals
            if ($scored['score'] < 10) {
                continue;
            }

            $profile = $user->role === 'trainee' ? $user->traineeProfile : $user->trainerProfile;

            $results[] = [
                'user' => $user,
                'profile' => $profile,
                'score' => $scored['score'],
                'evidence' => $scored['evidence'],
                'expertise_areas' => $scored['expertise_areas'],
                'recommended_role' => $scored['recommended_role'],
                'breakdown' => $scored['breakdown'],
            ];
        }

        // Sort by score descending
        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        return view('dashboards.admin.expert-discovery.index', compact('results', 'query', 'minScore'));
    }

    /**
     * Verify expert (mark as verified).
     */
    public function verify(Request $request, User $user)
    {
        $user->update(['expert_verified' => true]);

        return redirect()->back()->with('success', "{$user->name} has been marked as a Verified Expert.");
    }

    /**
     * Assign trainer or mentor role to expert (does NOT auto-promote, requires Admin action).
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate(['expert_role' => 'required|in:trainer,mentor']);
        $user->update(['expert_role' => $request->expert_role]);

        return redirect()->back()->with('success', "{$user->name} has been assigned the role of {$request->expert_role}.");
    }

    /**
     * Add expert to Knowledge Repository.
     */
    public function addToRepo(User $user)
    {
        $user->update(['in_knowledge_repo' => true]);

        return redirect()->back()->with('success', "{$user->name} has been added to the Knowledge Repository.");
    }
}
