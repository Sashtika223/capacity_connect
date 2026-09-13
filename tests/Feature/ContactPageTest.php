<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_page_can_be_rendered(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertSee('Get in Touch with Headquarters');
        $response->assertSee('Send an Inquiry');
    }

    public function test_contact_inquiry_can_be_submitted(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Dr. Jane Doe',
            'email' => 'jane.doe@agency.gov',
            'department' => 'Disaster Response & Management',
            'subject' => 'Emergency Operations Training Inquiry',
            'message' => 'We require additional capacity building modules for regional emergency coordinators.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
