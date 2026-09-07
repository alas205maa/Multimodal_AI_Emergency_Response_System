<?php

namespace Tests\Feature;

use App\Models\EmergencyReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public pages render successfully with 200 status.
     */
    public function test_public_pages_return_successful_response(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Emergency Response');
        $this->get('/about')->assertStatus(200)->assertSee('About the Emergency Response System');
        $this->get('/how-it-works')->assertStatus(200)->assertSee('How The System Works');
        $this->get('/contact')->assertStatus(200)->assertSee('Contact Project Team');
    }

    /**
     * Test authentication views render successfully.
     */
    public function test_auth_pages_return_successful_response(): void
    {
        $this->get('/login')->assertStatus(200)->assertSee('Sign In');
        $this->get('/register')->assertStatus(200)->assertSee('Create Citizen Account');
        $this->get('/forgot-password')->assertStatus(200)->assertSee('Reset Password');
        $this->get('/reset-password')->assertStatus(200)->assertSee('Set New Password');
        $this->get('/responder/login')->assertStatus(200)->assertSee('Emergency Response Portal');
    }

    /**
     * Test citizen portal screens render successfully.
     */
    public function test_citizen_pages_return_successful_response(): void
    {
        $this->get('/dashboard')->assertStatus(200)->assertSee('Citizen Dashboard');
        $this->get('/reports')->assertStatus(200)->assertSee('My Emergency Reports');
        $this->get('/reports/create')->assertStatus(200)->assertSee('Report an Emergency');
        $this->get('/reports/1042')->assertStatus(200)->assertSee('Emergency Report #1042');
        $this->get('/reports/1042/submitted')->assertStatus(200)->assertSee('Emergency Report Submitted');
        $this->get('/notifications')->assertStatus(200)->assertSee('Notifications');
        $this->get('/profile')->assertStatus(200)->assertSee('Citizen Profile');
    }

    /**
     * Test emergency report creation via form submission.
     */
    public function test_citizen_can_submit_emergency_report(): void
    {
        $payload = [
            'description' => 'Severe electrical fire with sparks near building substation',
            'latitude' => 24.713556,
            'longitude' => 46.675295,
        ];

        $response = $this->post('/reports', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('emergency_reports', [
            'description' => 'Severe electrical fire with sparks near building substation',
        ]);
    }

    /**
     * Test responder portal screens render successfully.
     */
    public function test_responder_pages_return_successful_response(): void
    {
        $this->get('/responder/dashboard')->assertStatus(200)->assertSee('Emergency Operations Dashboard');
        $this->get('/responder/emergencies')->assertStatus(200)->assertSee('Emergency Incidents Triage');
        $this->get('/responder/emergencies/1042')->assertStatus(200)->assertSee('Incident #1042');
        $this->get('/responder/map')->assertStatus(200)->assertSee('Tactical Incident Map');
        $this->get('/responder/analytics')->assertStatus(200)->assertSee('Operational Intelligence');
        $this->get('/responder/notifications')->assertStatus(200)->assertSee('Command Notifications');
        $this->get('/responder/profile')->assertStatus(200)->assertSee('Responder Profile');
        $this->get('/responder/settings')->assertStatus(200)->assertSee('System & Operations Settings');
    }

    /**
     * Test responder status update.
     */
    public function test_responder_can_update_incident_status(): void
    {
        $report = EmergencyReport::create([
            'description' => 'Road blockage',
            'latitude' => 24.713556,
            'longitude' => 46.675295,
            'status' => 'pending',
        ]);

        $response = $this->post("/responder/emergencies/{$report->id}/status", [
            'status' => 'In Progress',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('in progress', $report->fresh()->status);
    }
}
