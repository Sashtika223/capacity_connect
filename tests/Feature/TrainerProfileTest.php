<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_trainer_can_update_profile()
    {
        $trainer = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'trainer.test@example.com',
            'role' => 'trainer',
            'trainer_status' => 'approved',
            'status' => 'active',
        ]);

        Storage::fake('public');
        $photo = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($trainer)->post(route('trainer.profile.update'), [
            'name' => 'Updated Trainer Name',
            'photo' => $photo,
            'qualification' => 'Ph.D. Disaster Management',
            'experience' => '10 Years Field Experience',
            'expertise' => 'Emergency Rescue & Flood Control',
            'department' => 'Disaster Preparedness Authority',
            'bio' => 'Experienced disaster management specialist with 10 years of field experience.',
            'subjects' => 'Flood Control, Emergency Logistics, Cyclone Warning',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $trainer->refresh();
        $this->assertEquals('Updated Trainer Name', $trainer->name);

        $profile = $trainer->trainerProfile;
        $this->assertNotNull($profile);
        $this->assertEquals('Ph.D. Disaster Management', $profile->qualification);
        $this->assertEquals('10 Years Field Experience', $profile->experience);
        $this->assertEquals('Emergency Rescue & Flood Control', $profile->expertise);
        $this->assertEquals('Disaster Preparedness Authority', $profile->department);
        $this->assertEquals(100, $profile->completion_percentage);
    }
}
