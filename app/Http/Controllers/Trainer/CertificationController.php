<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\TrainerCertification;
use App\Models\TrainerCertificationAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->trainerProfile;

        $isProfileComplete = $profile ? $profile->isComplete() : false;
        $completionPercentage = $profile ? $profile->completion_percentage : 0;

        $certifications = TrainerCertification::where('user_id', $user->id)
            ->with(['course', 'attempts'])
            ->get();

        $attempts = TrainerCertificationAttempt::whereHas('certification', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->with(['certification.course'])
            ->latest()
            ->get();

        return view('dashboards.trainer.certifications.index', compact(
            'user', 'profile', 'isProfileComplete', 'completionPercentage', 'certifications', 'attempts'
        ));
    }

    public function show($id)
    {
        $user = Auth::user();
        $certification = TrainerCertification::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['course', 'attempts'])
            ->firstOrFail();

        $evaluation = session('evaluation');
        if (! $evaluation) {
            $isRenewal = in_array($certification->calculated_status, ['renewal_required', 'renewal_passed', 'renewal_failed']);
            $questions = $isRenewal
                ? $this->getRenewalQuestions($certification->course->title)
                : $this->getInitialQuestions($certification->course->title);

            $evalQuestions = [];
            foreach ($questions as $q) {
                $evalQuestions[] = [
                    'id' => $q['id'],
                    'question' => $q['question'],
                    'options' => $q['options'],
                    'selected' => $q['correct'],
                    'correct' => $q['correct'],
                    'is_correct' => true,
                    'explanation' => $q['explanation'],
                ];
            }

            $evaluation = [
                'score' => $certification->score ?? 0,
                'passed' => in_array($certification->status, ['certified', 'renewal_passed']),
                'type' => $isRenewal ? 'renewal' : 'initial',
                'questions' => $evalQuestions,
            ];
        }

        return view('dashboards.trainer.certifications.show', compact('certification', 'evaluation'));
    }

    protected function getInitialQuestions(string $courseTitle): array
    {
        return [
            [
                'id' => 1,
                'question' => 'During a live scenario drill on '.$courseTitle.', how do you establish instructional control during emergency communications breakdown?',
                'options' => [
                    'A' => 'Deploy secondary satellite transmission protocol and re-synchronize team channels.',
                    'B' => 'Halt all training sessions and wait for external IT support.',
                    'C' => 'Proceed without communication protocols.',
                    'D' => 'Disregard scenario guidelines and pass all trainees automatically.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because secondary satellite protocols provide failover connectivity independent of terrestrial infrastructure, enabling immediate re-synchronization of tactical communication channels.',
            ],
            [
                'id' => 2,
                'question' => 'What is the primary objective of real-time disaster-response practical drills?',
                'options' => [
                    'A' => 'Test procedural memory under simulated high-stress conditions.',
                    'B' => 'Fulfill bureaucratic paperwork requirements.',
                    'C' => 'Generate public relations media content.',
                    'D' => 'Delay operational readiness timelines.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because disaster drills stress-test automatic procedural execution and decision-making resilience under operational pressure, ensuring readiness during actual emergency events.',
            ],
            [
                'id' => 3,
                'question' => 'How should a certified trainer evaluate non-compliant field responses during a practical assessment?',
                'options' => [
                    'A' => 'Document safety violations, deliver immediate corrective feedback, and re-test key competencies.',
                    'B' => 'Ignore safety violations if the exercise finishes on schedule.',
                    'C' => 'Expel trainees permanently without remediation.',
                    'D' => 'Mutate assessment criteria on the fly without record.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because immediate corrective feedback rectifies critical safety errors on site, while documenting violations and re-testing competencies guarantees 100% compliance.',
            ],
            [
                'id' => 4,
                'question' => 'What documentation is required prior to signing off on high-risk capacity building certifications?',
                'options' => [
                    'A' => 'Verified 100% profile completion, practical score log, and scenario attempt record.',
                    'B' => 'Only verbal confirmation from the trainee.',
                    'C' => 'No documentation is required.',
                    'D' => 'Unverified third-party testimonials.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because complete documentation provides an auditable trail of trainer eligibility, verified profile credentials, and quantitative scenario attempt records.',
            ],
            [
                'id' => 5,
                'question' => 'What is the minimum passing score mandated for Trainer Scenario Certification?',
                'options' => [
                    'A' => '80%',
                    'B' => '50%',
                    'C' => '60%',
                    'D' => '70%',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because institutional disaster response standards mandate an 80% minimum score threshold to certify trainer competence in critical field scenarios.',
            ],
        ];
    }

    protected function getRenewalQuestions(string $courseTitle): array
    {
        return [
            [
                'id' => 1,
                'question' => 'For annual re-certification in '.$courseTitle.', how should dynamic environmental hazard factors be integrated into training updates?',
                'options' => [
                    'A' => 'Incorporate updated GIS hazard maps and latest field operational protocol.',
                    'B' => 'Reuse 5-year-old static slide decks.',
                    'C' => 'Omit risk assessments to save session time.',
                    'D' => 'Rely solely on informal verbal anecdotes.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because integrating real-time GIS hazard mapping ensures training scenarios reflect current environmental risks and updated operational protocols.',
            ],
            [
                'id' => 2,
                'question' => 'What is required when renewing trainer credentials after 1 year of active deployment?',
                'options' => [
                    'A' => 'Demonstrate minimum 80% mastery on scenario renewal assessment.',
                    'B' => 'Payment of arbitrary renewal fees.',
                    'C' => 'No re-evaluation is necessary.',
                    'D' => 'Bypass tests by filing a waiver.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because annual renewal requires re-evaluating practical scenario proficiency to maintain active trainer standing.',
            ],
            [
                'id' => 3,
                'question' => 'How are renewal attempts tracked in the institutional LMS system?',
                'options' => [
                    'A' => 'Preserved in full relational history without overwriting past attempts.',
                    'B' => 'Overwritten and deleted permanently.',
                    'C' => 'Stored in temporary unbacked cache.',
                    'D' => 'Manually recorded on paper logs.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because audit logging standards require storing immutable historical logs of all initial and renewal attempts for compliance tracking.',
            ],
            [
                'id' => 4,
                'question' => 'Which notification is generated upon successful trainer certification renewal?',
                'options' => [
                    'A' => 'Renewal Pass notification and updated validity timestamp extension.',
                    'B' => 'Account termination alert.',
                    'C' => 'No system record.',
                    'D' => 'Public penalty warning.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because passing the renewal test triggers automated system notifications and extends certification validity by 1 full year.',
            ],
            [
                'id' => 5,
                'question' => 'What happens if a trainer fails a renewal scenario test?',
                'options' => [
                    'A' => 'Status transitions to renewal_failed and credentials remain expired until re-test.',
                    'B' => 'Status automatically becomes certified.',
                    'C' => 'The system deletes the user account.',
                    'D' => 'The course is deleted.',
                ],
                'correct' => 'A',
                'explanation' => 'Option A is correct because failing a renewal assessment flags the trainer status as renewal_failed, requiring remedial re-testing to restore active certification.',
            ],
        ];
    }

    public function takeTest($id)
    {
        $user = Auth::user();
        $certification = TrainerCertification::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['course'])
            ->firstOrFail();

        $questions = $this->getInitialQuestions($certification->course->title);

        return view('dashboards.trainer.certifications.test', compact('certification', 'questions'));
    }

    public function submitTest(Request $request, $id)
    {
        $user = Auth::user();
        $certification = TrainerCertification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $questions = $this->getInitialQuestions($certification->course->title);
        $submittedAnswers = $request->input('answers', []);

        $correctCount = 0;
        $totalQuestions = count($questions);
        $evalDetails = [];

        foreach ($questions as $q) {
            $qId = $q['id'];
            $selectedOpt = isset($submittedAnswers[$qId]) ? strtoupper($submittedAnswers[$qId]) : 'NOT_ANSWERED';
            $isCorrect = ($selectedOpt === $q['correct']);

            if ($isCorrect) {
                $correctCount++;
            }

            $evalDetails[] = [
                'id' => $qId,
                'question' => $q['question'],
                'options' => $q['options'],
                'selected' => $selectedOpt,
                'correct' => $q['correct'],
                'is_correct' => $isCorrect,
                'explanation' => $q['explanation'],
            ];
        }

        $percentage = (int) round(($correctCount / $totalQuestions) * 100);
        $passed = $percentage >= 80;

        // Record attempt history
        TrainerCertificationAttempt::create([
            'trainer_certification_id' => $certification->id,
            'user_id' => $user->id,
            'type' => 'initial',
            'score' => $percentage,
            'passed' => $passed,
        ]);

        if ($passed) {
            $issuedAt = now();
            $expiresAt = now()->addYear();

            $certification->update([
                'status' => 'certified',
                'score' => $percentage,
                'issued_at' => $issuedAt,
                'expires_at' => $expiresAt,
            ]);

            // Issue Certificate Record
            Certificate::firstOrCreate(
                ['certificate_id' => 'CC-TRN-CERT-'.$certification->id.'-'.rand(1000, 9999)],
                [
                    'user_id' => $user->id,
                    'course_id' => $certification->course_id,
                    'certificate_name' => 'Certified Trainer: '.$certification->course->title,
                    'issuing_organization' => 'National Capacity Building Authority',
                    'score' => $percentage,
                    'cert_status' => 'valid',
                    'issue_date' => $issuedAt,
                    'expiry_date' => $expiresAt,
                ]
            );

            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => 'App\Notifications\CertificationPassedNotification',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Certification Passed & Issued!',
                    'message' => 'Congratulations! You achieved '.$percentage.'% and earned official trainer certification for '.$certification->course->title.'.',
                    'link' => route('trainer.certifications.show', $certification->id),
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            session()->flash('evaluation', [
                'score' => $percentage,
                'passed' => true,
                'type' => 'initial',
                'questions' => $evalDetails,
            ]);

            return redirect()->route('trainer.certifications.show', $certification->id)
                ->with('success', 'Congratulations! You passed the certification assessment ('.$percentage.'%). Certificate issued successfully.');
        } else {
            $certification->update([
                'status' => 'rejected',
                'score' => $percentage,
            ]);

            session()->flash('evaluation', [
                'score' => $percentage,
                'passed' => false,
                'type' => 'initial',
                'questions' => $evalDetails,
            ]);

            return redirect()->route('trainer.certifications.show', $certification->id)
                ->with('error', 'Assessment score of '.$percentage.'% did not meet the 80% passing threshold. Status set to Rejected.');
        }
    }

    public function renewalTest($id)
    {
        $user = Auth::user();
        $certification = TrainerCertification::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['course'])
            ->firstOrFail();

        $questions = $this->getRenewalQuestions($certification->course->title);

        return view('dashboards.trainer.certifications.renewal', compact('certification', 'questions'));
    }

    public function submitRenewal(Request $request, $id)
    {
        $user = Auth::user();
        $certification = TrainerCertification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $questions = $this->getRenewalQuestions($certification->course->title);
        $submittedAnswers = $request->input('answers', []);

        $correctCount = 0;
        $totalQuestions = count($questions);
        $evalDetails = [];

        foreach ($questions as $q) {
            $qId = $q['id'];
            $selectedOpt = isset($submittedAnswers[$qId]) ? strtoupper($submittedAnswers[$qId]) : 'NOT_ANSWERED';
            $isCorrect = ($selectedOpt === $q['correct']);

            if ($isCorrect) {
                $correctCount++;
            }

            $evalDetails[] = [
                'id' => $qId,
                'question' => $q['question'],
                'options' => $q['options'],
                'selected' => $selectedOpt,
                'correct' => $q['correct'],
                'is_correct' => $isCorrect,
                'explanation' => $q['explanation'],
            ];
        }

        $percentage = (int) round(($correctCount / $totalQuestions) * 100);
        $passed = $percentage >= 80;

        // Record Renewal History Attempt
        TrainerCertificationAttempt::create([
            'trainer_certification_id' => $certification->id,
            'user_id' => $user->id,
            'type' => 'renewal',
            'score' => $percentage,
            'passed' => $passed,
        ]);

        if ($passed) {
            $issuedAt = now();
            $expiresAt = now()->addYear();

            $certification->update([
                'status' => 'renewal_passed',
                'score' => $percentage,
                'issued_at' => $issuedAt,
                'expires_at' => $expiresAt,
            ]);

            session()->flash('evaluation', [
                'score' => $percentage,
                'passed' => true,
                'type' => 'renewal',
                'questions' => $evalDetails,
            ]);

            return redirect()->route('trainer.certifications.show', $certification->id)
                ->with('success', 'Certification renewed successfully! Extended validity until '.$expiresAt->format('d M Y').'.');
        } else {
            $certification->update([
                'status' => 'renewal_failed',
                'score' => $percentage,
            ]);

            session()->flash('evaluation', [
                'score' => $percentage,
                'passed' => false,
                'type' => 'renewal',
                'questions' => $evalDetails,
            ]);

            return redirect()->route('trainer.certifications.show', $certification->id)
                ->with('error', 'Renewal test score ('.$percentage.'%) did not meet passing criteria. Certification status set to Renewal Failed.');
        }
    }
}
