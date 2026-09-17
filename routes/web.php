<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AssessmentStatsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CapacitySimulatorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificateManagementController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\CompetencyMappingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpertDiscoveryController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\HomepageSettingsController;
use App\Http\Controllers\Admin\KnowledgeGraphController;
use App\Http\Controllers\Admin\RiskRadarController;
use App\Http\Controllers\Admin\TrainerCertificationController;
use App\Http\Controllers\Admin\TrainerRequestController;
use App\Http\Controllers\Admin\TrainingImpactController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AiAssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Public\VerificationController;
use App\Http\Controllers\PublicAnnouncementController;
use App\Http\Controllers\SubjectMatchingController;
use App\Http\Controllers\Trainee\CertificateController;
use App\Http\Controllers\Trainee\DigitalTwinController;
use App\Http\Controllers\Trainee\LearningAssistantController;
use App\Http\Controllers\Trainee\NotificationController;
use App\Http\Controllers\Trainer\AiStudioController;
use App\Http\Controllers\Trainer\AssessmentController;
use App\Http\Controllers\Trainer\CertificationController;
use App\Http\Controllers\Trainer\LessonController;
use App\Http\Controllers\Trainer\ModuleController;
use App\Http\Controllers\Trainer\PracticalAssessmentController;
use App\Http\Controllers\Trainer\ProfileController;
use App\Http\Controllers\Trainer\QuestionController;
use App\Http\Controllers\Trainer\ResourceController;
use App\Http\Middleware\EnsureTrainerProfileComplete;
use App\Mail\TrainerRegistrationNotification;
use App\Models\User;
use App\Services\HttpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Gemini AI Assistant Chat Endpoint (Available for guests & users)
Route::post('/api/ai-chat', [AiAssessmentController::class, 'chatReply'])->name('ai.chat');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('/assessments/generate-ai', [AiAssessmentController::class, 'generateAssessment'])->name('assessments.generate-ai');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');

        // Courses & Categories
        Route::resource('courses', CourseController::class)->except(['show']);
        Route::patch('/courses/{course}/feature', [CourseController::class, 'toggleFeature'])->name('courses.toggleFeature');
        Route::patch('/courses/{course}/archive', [CourseController::class, 'toggleArchive'])->name('courses.toggleArchive');

        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('announcements', AnnouncementController::class)->except(['show']);
        Route::resource('competencies', CompetencyController::class)->except(['create', 'show', 'edit']);

        Route::get('/competency-mapping', [CompetencyMappingController::class, 'index'])->name('competency-mapping.index');
        Route::post('/competency-mapping/user', [CompetencyMappingController::class, 'mapUser'])->name('competency-mapping.mapUser');
        Route::post('/competency-mapping/course', [CompetencyMappingController::class, 'mapCourse'])->name('competency-mapping.mapCourse');

        Route::get('/expert-discovery', [ExpertDiscoveryController::class, 'index'])->name('expert-discovery.index');
        Route::post('/expert-discovery/{user}/verify', [ExpertDiscoveryController::class, 'verify'])->name('expert-discovery.verify');
        Route::post('/expert-discovery/{user}/assign-role', [ExpertDiscoveryController::class, 'assignRole'])->name('expert-discovery.assign-role');
        Route::post('/expert-discovery/{user}/add-to-repo', [ExpertDiscoveryController::class, 'addToRepo'])->name('expert-discovery.add-to-repo');
        Route::get('/risk-radar', [RiskRadarController::class, 'index'])->name('risk-radar.index');

        Route::get('/capacity-simulator', [CapacitySimulatorController::class, 'index'])->name('capacity-simulator.index');
        Route::post('/capacity-simulator', [CapacitySimulatorController::class, 'store'])->name('capacity-simulator.store');
        Route::delete('/capacity-simulator/{id}', [CapacitySimulatorController::class, 'destroy'])->name('capacity-simulator.destroy');

        Route::get('/training-impact', [TrainingImpactController::class, 'index'])->name('training-impact.index');

        Route::get('/assessments', [AssessmentStatsController::class, 'index'])->name('assessments');

        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback');
        Route::patch('/feedback/{feedback}', [FeedbackController::class, 'updateStatus'])->name('feedback.update');
        Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');

        Route::get('/knowledge-graph', [KnowledgeGraphController::class, 'index'])->name('knowledge-graph.index');
        Route::get('/knowledge-graph/data', [KnowledgeGraphController::class, 'graphData'])->name('knowledge-graph.data');

        Route::get('/certificate-management', [CertificateManagementController::class, 'index'])->name('certificate-management.index');
        Route::post('/certificate-management/{certificate}/verify-renewal', [CertificateManagementController::class, 'verifyRenewal'])->name('certificate-management.verify-renewal');

        // Audit Logs (Section 35) & Homepage Settings (Section 33)
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/homepage-settings', [HomepageSettingsController::class, 'index'])->name('homepage-settings.index');
        Route::put('/homepage-settings', [HomepageSettingsController::class, 'update'])->name('homepage-settings.update');

        // Trainer Certification Management
        Route::get('/trainer-certifications', [TrainerCertificationController::class, 'index'])->name('trainer-certifications.index');
        Route::get('/trainer-certifications/{user}', [TrainerCertificationController::class, 'showTrainer'])->name('trainer-certifications.show');
        Route::post('/trainer-certifications/assign', [TrainerCertificationController::class, 'assign'])->name('trainer-certifications.assign');

        // Trainer Registration Approval Requests Workflow
        Route::get('/trainer-requests', [TrainerRequestController::class, 'index'])->name('trainer-requests.index');
        Route::post('/trainer-requests/{user}/approve', [TrainerRequestController::class, 'approve'])->name('trainer-requests.approve');
        Route::post('/trainer-requests/{user}/reject', [TrainerRequestController::class, 'reject'])->name('trainer-requests.reject');

        // Temporary Admin Test Email Route
        Route::get('/test-email', function (Request $request) {
            $to = $request->get('to', 'admin.capacity.connect.lms@gmail.com');
            $dummyUser = new User(['name' => 'Test Recipient', 'email' => $to]);
            $sent = HttpMailService::send($to, new TrainerRegistrationNotification($dummyUser), 'Test Recipient');

            return response()->json([
                'success' => $sent,
                'recipient' => $to,
                'message' => $sent ? "Real test email sent via Brevo to {$to}!" : 'Failed to send test email. Check logs.',
            ]);
        })->name('test-email');
    });

    Route::middleware(['role:trainer', EnsureTrainerProfileComplete::class])->prefix('trainer')->name('trainer.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Trainer\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Trainer Certification Sessions & History
        Route::get('/certifications', [CertificationController::class, 'index'])->name('certifications.index');
        Route::get('/certifications/{id}', [CertificationController::class, 'show'])->name('certifications.show');
        Route::get('/certifications/{id}/test', [CertificationController::class, 'takeTest'])->name('certifications.test');
        Route::post('/certifications/{id}/submit', [CertificationController::class, 'submitTest'])->name('certifications.submit');
        Route::post('/certifications/{id}/submit-test', [CertificationController::class, 'submitTest'])->name('certifications.submit-test');
        Route::get('/certifications/{id}/renewal', [CertificationController::class, 'renewalTest'])->name('certifications.renewal');
        Route::post('/certifications/{id}/renewal/submit', [CertificationController::class, 'submitRenewal'])->name('certifications.renewal.submit');
        Route::post('/certifications/{id}/submit-renewal', [CertificationController::class, 'submitRenewal'])->name('certifications.submit-renewal');

        // Course Management
        Route::get('/courses', [App\Http\Controllers\Trainer\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [App\Http\Controllers\Trainer\CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [App\Http\Controllers\Trainer\CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [App\Http\Controllers\Trainer\CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [App\Http\Controllers\Trainer\CourseController::class, 'update'])->name('courses.update');
        Route::get('/courses/{course}/enrollees', [App\Http\Controllers\Trainer\CourseController::class, 'enrollees'])->name('courses.enrollees');

        // Modules and Lessons
        Route::post('/courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
        Route::post('/modules/{module}/lessons', [LessonController::class, 'store'])->name('lessons.store');

        // Resources
        Route::post('/lessons/{lesson}/resources', [ResourceController::class, 'store'])->name('resources.store');

        Route::get('/courses/{course}/manage-assessments', [AssessmentController::class, 'manage'])->name('courses.assessments.manage');
        Route::post('/courses/{course}/assessments', [AssessmentController::class, 'store'])->name('courses.assessments.store');
        Route::post('/assessments/generate-ai', [AiAssessmentController::class, 'generateAssessment'])->name('assessments.generate-ai');
        Route::post('/assessments/quick-generate', [AssessmentController::class, 'generateQuickAssessment'])->name('assessments.quick-generate');
        Route::resource('assessments', AssessmentController::class);
        Route::post('/assessments/{assessment}/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

        Route::get('/ai-studio', [AiStudioController::class, 'index'])->name('ai-studio.index');
        Route::post('/ai-studio/generate', [AiStudioController::class, 'generate'])->name('ai-studio.generate');
        Route::get('/ai-studio/draft/{draft}', [AiStudioController::class, 'showDraft'])->name('ai-studio.draft');
        Route::post('/ai-studio/draft/{draft}/approve', [AiStudioController::class, 'approveDraft'])->name('ai-studio.approve');

        // Practical Assessments (Disaster Simulations)
        Route::resource('practical', PracticalAssessmentController::class);
        Route::post('/practical/{practical}/nodes', [PracticalAssessmentController::class, 'storeNode'])->name('practical.nodes.store');
        Route::post('/nodes/{node}/options', [PracticalAssessmentController::class, 'storeOption'])->name('practical.options.store');
        Route::delete('/nodes/{node}', [PracticalAssessmentController::class, 'deleteNode'])->name('practical.nodes.destroy');
        Route::delete('/options/{option}', [PracticalAssessmentController::class, 'deleteOption'])->name('practical.options.destroy');
    });

    Route::middleware('role:trainee')->prefix('trainee')->name('trainee.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Trainee\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [App\Http\Controllers\Trainee\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\Trainee\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/courses', [App\Http\Controllers\Trainee\CourseController::class, 'index'])->name('courses');
        Route::get('/courses/{course}', [App\Http\Controllers\Trainee\CourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/enroll', [App\Http\Controllers\Trainee\CourseController::class, 'enroll'])->name('courses.enroll');

        Route::get('/courses/{course}/lessons/{lesson}', [App\Http\Controllers\Trainee\LessonController::class, 'show'])->name('courses.lesson');
        Route::post('/courses/{course}/lessons/{lesson}/complete', [App\Http\Controllers\Trainee\LessonController::class, 'complete'])->name('courses.lesson.complete');

        Route::get('/assessments', [App\Http\Controllers\Trainee\AssessmentController::class, 'index'])->name('assessments');
        Route::get('/assessments/{assessment}', [App\Http\Controllers\Trainee\AssessmentController::class, 'show'])->name('assessments.show');
        Route::post('/assessments/{assessment}', [App\Http\Controllers\Trainee\AssessmentController::class, 'submit'])->name('assessments.submit');
        Route::get('/assessments/attempts/{attempt}', [App\Http\Controllers\Trainee\AssessmentController::class, 'result'])->name('assessments.result');

        // Practical Assessments (Disaster Simulations) Playback
        Route::get('/practical', [App\Http\Controllers\Trainee\PracticalAssessmentController::class, 'index'])->name('practical.index');
        Route::post('/practical/{practical}/start', [App\Http\Controllers\Trainee\PracticalAssessmentController::class, 'start'])->name('practical.start');
        Route::get('/practical/attempts/{attempt}', [App\Http\Controllers\Trainee\PracticalAssessmentController::class, 'play'])->name('practical.play');
        Route::post('/practical/attempts/{attempt}/choose', [App\Http\Controllers\Trainee\PracticalAssessmentController::class, 'choose'])->name('practical.choose');
        Route::get('/practical/attempts/{attempt}/result', [App\Http\Controllers\Trainee\PracticalAssessmentController::class, 'result'])->name('practical.result');

        Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates');
        Route::get('/certificates/{id}/view', [CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');
        Route::post('/certificates/{id}/renew', [CertificateController::class, 'uploadRenewal'])->name('certificates.renew');

        // Placeholder routes returning views for now
        Route::view('/enrollments', 'dashboards.trainee.enrollments')->name('enrollments');
        Route::get('/results', [App\Http\Controllers\Trainee\AssessmentController::class, 'results'])->name('results');
        Route::get('/feedback', [App\Http\Controllers\Trainee\FeedbackController::class, 'index'])->name('feedback');
        Route::post('/feedback', [App\Http\Controllers\Trainee\FeedbackController::class, 'store'])->name('feedback.store');
        Route::get('/competencies', [App\Http\Controllers\Trainee\CompetencyController::class, 'index'])->name('competencies.index');
        Route::get('/digital-twin', [DigitalTwinController::class, 'index'])->name('digital-twin.index');
        // AI Learning Assistant (Section 24)
        Route::get('/ai-assistant', [LearningAssistantController::class, 'index'])->name('assistant.index');
        Route::post('/ai-assistant/chat', [LearningAssistantController::class, 'chat'])->name('assistant.chat');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
        Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
        Route::post('/notifications/preferences', [NotificationController::class, 'updatePreferences'])->name('notifications.preferences.update');
        Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });
});

// Public Routes
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/search', [GlobalSearchController::class, 'index'])->name('search.index');
Route::get('/subject-matching', [SubjectMatchingController::class, 'index'])->name('subject-matching.index');
Route::get('/announcements', [PublicAnnouncementController::class, 'index'])->name('announcements.index');
Route::view('/offline', 'offline')->name('offline');
Route::get('/certificates/verify/{certificateId}', [VerificationController::class, 'verify'])->name('verify.certificate');
