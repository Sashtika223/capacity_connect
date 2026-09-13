<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\TrainerCertification;
use App\Models\TrainerCertificationAttempt;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TrainerCertificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $incompleteTrainer;

    protected User $completeTrainer;

    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['email' => 'admin.capacity.connect.lms@gmail.com', 'role' => 'admin']);

        // Trainer with incomplete profile
        $this->incompleteTrainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::create([
            'user_id' => $this->incompleteTrainer->id,
            'department' => 'Disaster Management',
            'qualification' => 'M.Sc. Safety',
            // Missing: photo, experience, expertise, bio, subjects
        ]);

        // Trainer with 100% complete profile
        $this->completeTrainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::create([
            'user_id' => $this->completeTrainer->id,
            'photo' => 'profile.jpg',
            'qualification' => 'Ph.D. Emergency Response',
            'experience' => '10 Years Field Operational Director',
            'expertise' => 'Crisis Communications & Logistics',
            'department' => 'Public Safety',
            'bio' => 'Certified master trainer in incident response.',
            'subjects' => 'Rapid Deployment, Logistics',
        ]);

        $this->course = Course::create([
            'title' => 'Emergency Response Protocol',
            'course_code' => 'ERP-101',
            'description' => 'Comprehensive emergency management training.',
            'trainer_id' => $this->completeTrainer->id,
            'status' => 'published',
            'publish_status' => 'published',
        ]);
    }

    public function test_unauthorized_admin_email_cannot_login_as_admin()
    {
        $fakeAdmin = User::factory()->create([
            'email' => 'fakeadmin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'fakeadmin@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_trainer_profile_completion_percentage_calculation()
    {
        $incompleteProfile = $this->incompleteTrainer->trainerProfile;
        $this->assertLessThan(100, $incompleteProfile->completion_percentage);
        $this->assertFalse($incompleteProfile->isComplete());
        $this->assertNotEmpty($incompleteProfile->missing_fields);

        $completeProfile = $this->completeTrainer->trainerProfile;
        $this->assertEquals(100, $completeProfile->completion_percentage);
        $this->assertTrue($completeProfile->isComplete());
        $this->assertEmpty($completeProfile->missing_fields);
    }

    public function test_incomplete_trainer_is_redirected_to_profile_page()
    {
        $response = $this->actingAs($this->incompleteTrainer)->get(route('trainer.dashboard'));
        $response->assertRedirect(route('trainer.profile'));
    }

    public function test_complete_trainer_can_access_dashboard_and_certifications()
    {
        $response = $this->actingAs($this->completeTrainer)->get(route('trainer.dashboard'));
        $response->assertStatus(200);

        $responseCerts = $this->actingAs($this->completeTrainer)->get(route('trainer.certifications.index'));
        $responseCerts->assertStatus(200);
    }

    public function test_admin_can_assign_certification_assessment_to_eligible_trainer()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.trainer-certifications.assign'), [
            'trainer_id' => $this->completeTrainer->id,
            'course_id' => $this->course->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trainer_certifications', [
            'user_id' => $this->completeTrainer->id,
            'course_id' => $this->course->id,
            'status' => 'assigned',
        ]);
    }

    public function test_admin_cannot_assign_certification_to_incomplete_trainer()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.trainer-certifications.assign'), [
            'trainer_id' => $this->incompleteTrainer->id,
            'course_id' => $this->course->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('trainer_certifications', [
            'user_id' => $this->incompleteTrainer->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_trainer_passing_initial_scenario_assessment_becomes_certified()
    {
        $cert = TrainerCertification::create([
            'user_id' => $this->completeTrainer->id,
            'course_id' => $this->course->id,
            'status' => 'assigned',
        ]);

        // Submit all correct answers ('A')
        $response = $this->actingAs($this->completeTrainer)->post(route('trainer.certifications.submit', $cert->id), [
            'answers' => [1 => 'A', 2 => 'A', 3 => 'A', 4 => 'A', 5 => 'A'],
        ]);

        $response->assertRedirect(route('trainer.certifications.show', $cert->id));
        $response->assertSessionHas('success');

        $cert->refresh();
        $this->assertEquals('certified', $cert->status);
        $this->assertEquals(100, $cert->score);
        $this->assertNotNull($cert->issued_at);
        $this->assertNotNull($cert->expires_at);

        $this->assertDatabaseHas('trainer_certification_attempts', [
            'trainer_certification_id' => $cert->id,
            'type' => 'initial',
            'score' => 100,
            'passed' => true,
        ]);
    }

    public function test_trainer_failing_initial_scenario_assessment_becomes_rejected()
    {
        $cert = TrainerCertification::create([
            'user_id' => $this->completeTrainer->id,
            'course_id' => $this->course->id,
            'status' => 'assigned',
        ]);

        // Submit incorrect answers ('B')
        $response = $this->actingAs($this->completeTrainer)->post(route('trainer.certifications.submit', $cert->id), [
            'answers' => [1 => 'B', 2 => 'B', 3 => 'B', 4 => 'B', 5 => 'B'],
        ]);

        $response->assertRedirect(route('trainer.certifications.show', $cert->id));
        $response->assertSessionHas('error');

        $cert->refresh();
        $this->assertEquals('rejected', $cert->status);
        $this->assertEquals(0, $cert->score);

        $this->assertDatabaseHas('trainer_certification_attempts', [
            'trainer_certification_id' => $cert->id,
            'type' => 'initial',
            'score' => 0,
            'passed' => false,
        ]);
    }

    public function test_renewal_assessment_extends_validity_and_logs_attempt_history()
    {
        $cert = TrainerCertification::create([
            'user_id' => $this->completeTrainer->id,
            'course_id' => $this->course->id,
            'status' => 'renewal_required',
            'score' => 100,
            'issued_at' => now()->subYear(),
            'expires_at' => now()->subDay(),
        ]);

        // Record initial attempt
        TrainerCertificationAttempt::create([
            'trainer_certification_id' => $cert->id,
            'user_id' => $this->completeTrainer->id,
            'type' => 'initial',
            'score' => 100,
            'passed' => true,
        ]);

        // Submit renewal test with passing answers ('A')
        $response = $this->actingAs($this->completeTrainer)->post(route('trainer.certifications.renewal.submit', $cert->id), [
            'answers' => [1 => 'A', 2 => 'A', 3 => 'A', 4 => 'A', 5 => 'A'],
        ]);

        $response->assertRedirect(route('trainer.certifications.show', $cert->id));
        $response->assertSessionHas('success');

        $cert->refresh();
        $this->assertEquals('renewal_passed', $cert->status);
        $this->assertEquals(100, $cert->score);
        $this->assertTrue($cert->expires_at->isFuture());

        // Check both initial and renewal attempts exist in history
        $this->assertCount(2, $cert->attempts);
        $this->assertDatabaseHas('trainer_certification_attempts', [
            'trainer_certification_id' => $cert->id,
            'type' => 'renewal',
            'score' => 100,
            'passed' => true,
        ]);
    }
}
