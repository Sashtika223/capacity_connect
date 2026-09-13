<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentOption;
use App\Models\AssessmentQuestion;
use App\Models\Certificate;
use App\Models\Competency;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Enrollment;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $trainer1;

    protected User $trainer2;

    protected User $trainee;

    protected CourseCategory $category;

    protected Course $course1;

    protected Course $course2;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup base entities
        $this->admin = User::factory()->create(['email' => 'admin.capacity.connect.lms@gmail.com', 'role' => 'admin', 'status' => 'active']);
        $this->trainer1 = User::factory()->create(['role' => 'trainer', 'status' => 'active']);
        $this->trainer2 = User::factory()->create(['role' => 'trainer', 'status' => 'active']);
        $this->trainee = User::factory()->create(['role' => 'trainee', 'status' => 'active']);

        TrainerProfile::create([
            'user_id' => $this->trainer1->id,
            'photo' => 't1.jpg',
            'qualification' => 'Ph.D.',
            'experience' => '10 Years',
            'expertise' => 'Logistics',
            'department' => 'Safety',
            'bio' => 'Bio',
            'subjects' => 'Logistics 101',
        ]);

        TrainerProfile::create([
            'user_id' => $this->trainer2->id,
            'photo' => 't2.jpg',
            'qualification' => 'M.Sc.',
            'experience' => '8 Years',
            'expertise' => 'Medical Response',
            'department' => 'Health',
            'bio' => 'Bio',
            'subjects' => 'Triage 101',
        ]);

        $this->category = CourseCategory::create([
            'name' => 'Emergency Logistics',
            'slug' => 'emergency-logistics',
            'description' => 'Logistics courses',
        ]);

        $this->course1 = Course::create([
            'title' => 'Trainer 1 Coastal Defense Course',
            'course_code' => 'CC-TR1-101',
            'description' => 'Course by Trainer 1',
            'category_id' => $this->category->id,
            'trainer_id' => $this->trainer1->id,
            'difficulty' => 'beginner',
            'duration' => 4,
            'publish_status' => 'published',
            'status' => 'approved',
        ]);

        $this->course2 = Course::create([
            'title' => 'Trainer 2 Urban Rescue Course',
            'course_code' => 'CC-TR2-101',
            'description' => 'Course by Trainer 2',
            'category_id' => $this->category->id,
            'trainer_id' => $this->trainer2->id,
            'difficulty' => 'advanced',
            'duration' => 8,
            'publish_status' => 'published',
            'status' => 'approved',
        ]);
    }

    /** 1. AUTHENTICATION TESTS */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => $this->trainee->email,
            'password' => 'password', // Default factory password
        ]);

        $this->assertAuthenticatedAs($this->trainee);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => $this->trainee->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /** 2. AUTHORIZATION & SECURITY BOUNDARY TESTS */
    public function test_trainee_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->trainee)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_trainee_cannot_access_trainer_dashboard(): void
    {
        $response = $this->actingAs($this->trainee)->get('/trainer/dashboard');
        $response->assertStatus(403);
    }

    public function test_trainer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->trainer1)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_unauthorized_guest_redirected_from_protected_routes(): void
    {
        $response = $this->get('/trainee/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_trainer_cannot_edit_another_trainers_course(): void
    {
        // Trainer 1 attempting to update Trainer 2's course via PUT /trainer/courses/{course}
        $response = $this->actingAs($this->trainer1)->put("/trainer/courses/{$this->course2->id}", [
            'title' => 'Hacked Title By Trainer 1',
            'description' => 'Unauthorized update attempt',
            'category_id' => $this->category->id,
            'difficulty' => 'advanced',
            'duration' => 10,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('courses', ['title' => 'Hacked Title By Trainer 1']);
    }

    /** 3. COURSE & ENROLLMENT TESTS */
    public function test_trainee_can_browse_courses(): void
    {
        $response = $this->actingAs($this->trainee)->get('/trainee/courses');
        $response->assertStatus(200);
        $response->assertSee('Trainer 1 Coastal Defense Course');
    }

    public function test_trainee_can_enroll_in_published_course(): void
    {
        $response = $this->actingAs($this->trainee)->post("/trainee/courses/{$this->course1->id}/enroll");
        $response->assertRedirect();

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->trainee->id,
            'course_id' => $this->course1->id,
        ]);
    }

    /** 4. ASSESSMENT & MCQS TESTS */
    public function test_trainee_can_submit_assessment_attempt(): void
    {
        Enrollment::create([
            'user_id' => $this->trainee->id,
            'course_id' => $this->course1->id,
            'status' => 'in_progress',
        ]);

        $assessment = Assessment::create([
            'course_id' => $this->course1->id,
            'title' => 'Coastal Defense Quiz',
            'duration' => 20,
            'passing_score' => 70,
            'status' => 'published',
        ]);

        $question = AssessmentQuestion::create([
            'assessment_id' => $assessment->id,
            'question_text' => 'What is coastal surge level 3?',
            'marks' => 10,
        ]);

        $optCorrect = AssessmentOption::create([
            'assessment_question_id' => $question->id,
            'option_text' => 'High tide flood alert',
            'is_correct' => true,
        ]);

        $optIncorrect = AssessmentOption::create([
            'assessment_question_id' => $question->id,
            'option_text' => 'Calm weather',
            'is_correct' => false,
        ]);

        AssessmentAttempt::create([
            'user_id' => $this->trainee->id,
            'assessment_id' => $assessment->id,
            'status' => 'in-progress',
            'start_time' => now(),
        ]);

        // Submit assessment answers via POST /trainee/assessments/{assessment}
        $response = $this->actingAs($this->trainee)->post("/trainee/assessments/{$assessment->id}", [
            'answers' => [
                $question->id => $optCorrect->id,
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assessment_attempts', [
            'user_id' => $this->trainee->id,
            'assessment_id' => $assessment->id,
            'status' => 'completed',
        ]);
    }

    /** 5. CERTIFICATE VERIFICATION TESTS */
    public function test_public_can_verify_certificate(): void
    {
        $cert = Certificate::create([
            'certificate_id' => 'VERIFY-TEST-999',
            'user_id' => $this->trainee->id,
            'course_id' => $this->course1->id,
            'certificate_name' => 'Coastal Defense Master',
            'issuing_organization' => 'National Disaster Center',
            'score' => 98,
            'cert_status' => 'valid',
            'issue_date' => now(),
        ]);

        $response = $this->get("/certificates/verify/{$cert->certificate_id}");
        $response->assertStatus(200);
        $response->assertSee('VERIFY-TEST-999');
        $response->assertSee('Valid');
    }

    /** 6. COMPETENCY TRACKING TESTS */
    public function test_trainee_competency_profile_calculates_gap(): void
    {
        $competency = Competency::create([
            'name' => 'Rapid Evacuation Protocol',
            'description' => 'Evacuation management',
        ]);

        $this->trainee->competencies()->attach($competency->id, [
            'current_level' => 2,
            'required_level' => 4,
        ]);

        $response = $this->actingAs($this->trainee)->get('/trainee/competencies');
        $response->assertStatus(200);
        $response->assertSee('Rapid Evacuation Protocol');
    }

    /** 7. ADMIN & TRAINER MANAGEMENT TESTS */
    public function test_trainer_can_access_assessments_index(): void
    {
        $response = $this->actingAs($this->trainer1)->get('/trainer/assessments');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_dashboard_and_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $responseUserMgmt = $this->actingAs($this->admin)->get('/admin/users');
        $responseUserMgmt->assertStatus(200);
    }
}
