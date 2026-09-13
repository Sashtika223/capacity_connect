<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeminiAiFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_chat_assistant_api_returns_response(): void
    {
        $response = $this->postJson(route('ai.chat'), [
            'message' => 'How do I enroll in a course?',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'reply', 'timestamp'])
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_generate_assessment_with_gemini_ai(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = CourseCategory::firstOrCreate(['name' => 'General Training', 'slug' => 'general-training']);
        $course = Course::create([
            'title' => 'Category 4 Cyclone Readiness',
            'course_code' => 'CC-CRS-999',
            'description' => 'Cyclone emergency preparedness and evacuation logistics.',
            'category_id' => $category->id,
            'trainer_id' => $admin->id,
            'duration' => 5,
            'publish_status' => 'published',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.assessments.generate-ai'), [
            'course_id' => $course->id,
            'pasted_content' => 'Emergency response unit evacuation guidelines and flood management protocols.',
            'difficulty' => 'medium',
            'num_questions' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('assessments', [
            'course_id' => $course->id,
            'status' => 'published',
        ]);
    }
}
