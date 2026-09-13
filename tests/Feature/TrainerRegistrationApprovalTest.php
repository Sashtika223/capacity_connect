<?php

namespace Tests\Feature;

use App\Mail\TrainerApprovedMail;
use App\Mail\TrainerRegistrationNotification;
use App\Mail\TrainerRejectedMail;
use App\Models\TrainerProfile;
use App\Models\User;
use App\Notifications\TrainerRegistrationRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TrainerRegistrationApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin.capacity.connect.lms@gmail.com',
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function test_trainer_registration_creates_pending_account_and_notifies_admin()
    {
        Mail::fake();
        Notification::fake();

        $response = $this->post(route('register'), [
            'name' => 'John Trainer',
            'email' => 'john.trainer@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'trainer',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'john.trainer@example.com',
            'role' => 'trainer',
            'trainer_status' => 'pending',
            'status' => 'inactive',
        ]);

        Mail::assertSent(TrainerRegistrationNotification::class, function ($mail) {
            return $mail->trainer->email === 'john.trainer@example.com';
        });

        Notification::assertSentTo(
            $this->admin,
            TrainerRegistrationRequestNotification::class
        );
    }

    public function test_trainee_registration_logs_in_immediately()
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Trainee',
            'email' => 'jane.trainee@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'trainee',
        ]);

        $response->assertRedirect(route('trainee.dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'jane.trainee@example.com',
            'role' => 'trainee',
            'status' => 'active',
        ]);
    }

    public function test_pending_trainer_cannot_login()
    {
        $pendingTrainer = User::factory()->create([
            'email' => 'pending.trainer@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'trainer',
            'trainer_status' => 'pending',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'pending.trainer@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_rejected_trainer_cannot_login()
    {
        $rejectedTrainer = User::factory()->create([
            'email' => 'rejected.trainer@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'trainer',
            'trainer_status' => 'rejected',
            'status' => 'inactive',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'rejected.trainer@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_view_trainer_requests()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.trainer-requests.index'));
        $response->assertStatus(200);
        $response->assertSee('Trainer Registration Requests');
    }

    public function test_admin_can_approve_trainer_request()
    {
        Mail::fake();

        $pendingTrainer = User::factory()->create([
            'name' => 'Alex Pending',
            'email' => 'alex.pending@example.com',
            'role' => 'trainer',
            'trainer_status' => 'pending',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.trainer-requests.approve', $pendingTrainer->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pendingTrainer->refresh();
        $this->assertEquals('approved', $pendingTrainer->trainer_status);
        $this->assertEquals('active', $pendingTrainer->status);
        $this->assertNotNull($pendingTrainer->approved_at);
        $this->assertEquals($this->admin->id, $pendingTrainer->approved_by);

        Mail::assertSent(TrainerApprovedMail::class, function ($mail) use ($pendingTrainer) {
            return $mail->hasTo($pendingTrainer->email);
        });
    }

    public function test_approved_trainer_can_login_normally()
    {
        $approvedTrainer = User::factory()->create([
            'email' => 'approved.trainer@example.com',
            'password' => Hash::make('Password123!'),
            'role' => 'trainer',
            'trainer_status' => 'approved',
            'status' => 'active',
        ]);

        TrainerProfile::create([
            'user_id' => $approvedTrainer->id,
            'photo' => 'photo.jpg',
            'qualification' => 'Ph.D.',
            'experience' => '5 Years',
            'expertise' => 'Logistics',
            'department' => 'Safety',
            'bio' => 'Bio',
            'subjects' => 'Logistics 101',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'approved.trainer@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('trainer.dashboard'));
        $this->assertAuthenticatedAs($approvedTrainer);
    }

    public function test_admin_can_reject_trainer_request_with_reason()
    {
        Mail::fake();

        $pendingTrainer = User::factory()->create([
            'name' => 'Chris Pending',
            'email' => 'chris.pending@example.com',
            'role' => 'trainer',
            'trainer_status' => 'pending',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.trainer-requests.reject', $pendingTrainer->id), [
            'rejection_reason' => 'Qualifications do not meet minimum department standard.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pendingTrainer->refresh();
        $this->assertEquals('rejected', $pendingTrainer->trainer_status);
        $this->assertEquals('inactive', $pendingTrainer->status);
        $this->assertNotNull($pendingTrainer->rejected_at);
        $this->assertEquals('Qualifications do not meet minimum department standard.', $pendingTrainer->rejection_reason);

        Mail::assertSent(TrainerRejectedMail::class, function ($mail) use ($pendingTrainer) {
            return $mail->hasTo($pendingTrainer->email) && $mail->rejectionReason === 'Qualifications do not meet minimum department standard.';
        });
    }

    public function test_non_admin_cannot_access_trainer_requests()
    {
        $trainee = User::factory()->create(['role' => 'trainee']);

        $response = $this->actingAs($trainee)->get(route('admin.trainer-requests.index'));
        $response->assertStatus(403);
    }
}
