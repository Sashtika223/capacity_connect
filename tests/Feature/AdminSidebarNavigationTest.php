<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSidebarNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_admin_sidebar_routes_load_successfully(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $routes = [
            'admin.dashboard',
            'admin.users.index',
            'admin.trainer-requests.index',
            'admin.courses.index',
            'admin.categories.index',
            'admin.assessments',
            'admin.certificate-management.index',
            'admin.trainer-certifications.index',
            'admin.training-impact.index',
            'admin.feedback',
            'admin.capacity-simulator.index',
            'admin.competencies.index',
            'admin.competency-mapping.index',
            'admin.risk-radar.index',
            'admin.expert-discovery.index',
            'admin.knowledge-graph.index',
            'admin.announcements.index',
            'admin.homepage-settings.index',
            'admin.audit-logs.index',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($admin)->get(route($routeName));
            if ($response->status() !== 200) {
                dump("Route {$routeName} failed with status {$response->status()}: ".substr(strip_tags($response->getContent()), 0, 500));
            }
            $this->assertEquals(200, $response->status(), "Route failed with status {$response->status()}: {$routeName}");
        }
    }

    public function test_admin_can_view_trainer_certifications_detail_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer']);

        $response = $this->actingAs($admin)->get(route('admin.trainer-certifications.show', $trainer->id));

        $response->assertStatus(200);
    }
}
