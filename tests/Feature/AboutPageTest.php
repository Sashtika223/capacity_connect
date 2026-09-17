<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_can_be_rendered(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSeeText('Empowering Organizations & Responders with Next-Gen Capacity Building');
        $response->assertSeeText('Mission, Vision & Strategic Values');
        $response->assertSeeText('Platform Core Capabilities');
    }
}
