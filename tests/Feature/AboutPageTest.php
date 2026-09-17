<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('Building Resilient Workforces for Emergency Readiness');
        $response->assertSee('Our Mission & Strategic Vision', false);
        $response->assertSee('Core Architecture & Capabilities', false);
    }
}
