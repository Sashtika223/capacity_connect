<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\Course;
use App\Models\User;

class RiskRadarController extends Controller
{
    public function index()
    {
        $competencies = Competency::with('users')->get();
        $trainees = User::where('role', 'trainee')->with(['competencies', 'certificates', 'assessmentAttempts', 'enrollments'])->get();

        $riskData = [];
        $userRisks = [];
        $metrics = [
            'total_mapped' => 0,
            'critical_risk' => 0,
            'high_risk' => 0,
            'medium_risk' => 0,
            'low_risk' => 0,
        ];

        // 1. Competency-Level Risk Radar with Explicit Factors
        foreach ($competencies as $comp) {
            $requiredCapacity = 0;
            $currentCapacity = 0;
            $reasons = [];

            foreach ($comp->users as $user) {
                $requiredCapacity += $user->pivot->required_level;
                $currentCapacity += $user->pivot->current_level;

                if ($user->pivot->current_level < $user->pivot->required_level) {
                    $levelLabels = [0 => 'None', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Advanced', 4 => 'Expert'];
                    $curLabel = $levelLabels[$user->pivot->current_level] ?? 'Basic';
                    $reqLabel = $levelLabels[$user->pivot->required_level] ?? 'Advanced';
                    $reasons[] = "{$comp->name} competency is required for {$user->name} at {$reqLabel} level but current proficiency is {$curLabel}.";
                }
            }

            if ($requiredCapacity > 0) {
                $metrics['total_mapped']++;
                $gap = $requiredCapacity - $currentCapacity;

                $riskLevel = 'LOW RISK';
                $riskColor = 'success';

                if ($gap > 0) {
                    $gapPercentage = ($gap / $requiredCapacity) * 100;

                    if ($gapPercentage > 50) {
                        $riskLevel = 'HIGH RISK';
                        $riskColor = 'danger';
                        $metrics['high_risk']++;
                    } elseif ($gapPercentage > 20) {
                        $riskLevel = 'MEDIUM RISK';
                        $riskColor = 'warning text-dark';
                        $metrics['medium_risk']++;
                    } else {
                        $riskLevel = 'LOW RISK';
                        $riskColor = 'info text-dark';
                        $metrics['low_risk']++;
                    }
                } else {
                    $metrics['low_risk']++;
                }

                $recommendedCourses = Course::where('publish_status', 'published')
                    ->whereHas('competencies', function ($q) use ($comp) {
                        $q->where('competency_id', $comp->id);
                    })->get();

                $riskData[] = [
                    'competency' => $comp,
                    'required_capacity' => $requiredCapacity,
                    'current_capacity' => $currentCapacity,
                    'gap' => $gap,
                    'risk_level' => $riskLevel,
                    'risk_color' => $riskColor,
                    'explanations' => $reasons,
                    'recommended_courses' => $recommendedCourses,
                ];
            }
        }

        // 2. Trainee Individual Risk Profile Audit
        foreach ($trainees as $trainee) {
            $traineeReasons = [];
            $riskPoints = 0;

            // Factor A: Missing Required Competencies
            foreach ($trainee->competencies as $c) {
                if ($c->pivot->current_level < $c->pivot->required_level) {
                    $riskPoints += 25;
                    $traineeReasons[] = "Required skill '{$c->name}' is below target level (Current: {$c->pivot->current_level}, Target: {$c->pivot->required_level}).";
                }
            }

            // Factor B: Expired Certifications
            $expiredCerts = $trainee->certificates->where('cert_status', 'expired');
            if ($expiredCerts->count() > 0) {
                $riskPoints += 30;
                foreach ($expiredCerts as $cert) {
                    $traineeReasons[] = "Certification for '{$cert->course->title}' expired on ".($cert->expiry_date ? $cert->expiry_date->format('M d, Y') : 'past date').'.';
                }
            }

            // Factor C: Low Assessment Performance
            $recentAttempts = $trainee->assessmentAttempts->where('status', 'completed');
            if ($recentAttempts->count() > 0) {
                $avgPercentage = $recentAttempts->avg('percentage');
                if ($avgPercentage < 50) {
                    $riskPoints += 20;
                    $traineeReasons[] = 'Low average assessment performance ('.round($avgPercentage).'%).';
                }
            }

            // Factor D: Long Period Without Training
            $latestCompletion = $trainee->enrollments->where('status', 'completed')->sortByDesc('updated_at')->first();
            if (! $latestCompletion || $latestCompletion->updated_at->diffInDays(now()) > 90) {
                $riskPoints += 15;
                $days = $latestCompletion ? $latestCompletion->updated_at->diffInDays(now()) : '90+';
                $traineeReasons[] = "No completed training recorded in the past {$days} days.";
            }

            if (count($traineeReasons) > 0) {
                $userRiskLevel = 'LOW RISK';
                $userRiskColor = 'success';
                if ($riskPoints >= 40) {
                    $userRiskLevel = 'HIGH RISK';
                    $userRiskColor = 'danger';
                } elseif ($riskPoints >= 20) {
                    $userRiskLevel = 'MEDIUM RISK';
                    $userRiskColor = 'warning';
                }

                $userRisks[] = [
                    'user' => $trainee,
                    'risk_level' => $userRiskLevel,
                    'risk_color' => $userRiskColor,
                    'risk_points' => $riskPoints,
                    'explanations' => $traineeReasons,
                ];
            }
        }

        return view('dashboards.admin.risk-radar.index', compact('riskData', 'userRisks', 'metrics'));
    }
}
