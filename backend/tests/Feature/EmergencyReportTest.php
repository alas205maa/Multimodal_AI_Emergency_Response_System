<?php

namespace Tests\Feature;

use App\Models\EmergencyReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the emergency_reports table exists and can store a record.
     */
    public function test_emergency_report_can_be_created(): void
    {
        $report = EmergencyReport::create([
            'description' => 'Fire in building A',
            'latitude' => 24.71355600,
            'longitude' => 46.67529500,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('emergency_reports', [
            'id' => $report->id,
            'description' => 'Fire in building A',
            'status' => 'pending',
        ]);
    }

    /**
     * Test that status defaults to 'pending' when not provided.
     */
    public function test_emergency_report_status_defaults_to_pending(): void
    {
        $report = EmergencyReport::create([
            'description' => 'Flood on main road',
            'latitude' => 24.71355600,
            'longitude' => 46.67529500,
        ]);

        $this->assertEquals('pending', $report->fresh()->status);
    }

    /**
     * Test that image field is nullable.
     */
    public function test_emergency_report_image_is_nullable(): void
    {
        $report = EmergencyReport::create([
            'description' => 'Car accident',
            'latitude' => 24.71355600,
            'longitude' => 46.67529500,
        ]);

        $this->assertNull($report->image);
    }

    /**
     * Test that image field can store a path.
     */
    public function test_emergency_report_can_store_image_path(): void
    {
        $report = EmergencyReport::create([
            'description' => 'Building collapse',
            'image' => 'images/reports/collapse_001.jpg',
            'latitude' => 24.71355600,
            'longitude' => 46.67529500,
        ]);

        $this->assertEquals('images/reports/collapse_001.jpg', $report->image);
    }
}
