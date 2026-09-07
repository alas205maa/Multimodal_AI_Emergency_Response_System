<?php

namespace App\Services;

use App\Models\EmergencyReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Throwable;

class EmergencyDemoService
{
    /**
     * Get a collection of emergency reports, using real DB records if available,
     * supplemented with realistic demo AI and status attributes.
     */
    public function getReports(): Collection
    {
        $realReports = collect();

        try {
            $realReports = EmergencyReport::latest()->take(20)->get();
        } catch (Throwable $e) {
            // Database may be offline or unseeded in local dev; fallback gracefully
            $realReports = collect();
        }

        $demoReports = $this->getDefaultDemoReports();

        if ($realReports->isEmpty()) {
            return $demoReports;
        }

        // Map real reports and decorate with mock AI attributes
        $decoratedReal = $realReports->map(function ($report, $index) {
            return $this->decorateReport($report, $index);
        });

        // Merge to ensure a rich list for demonstration
        return $decoratedReal->concat($demoReports->slice($decoratedReal->count()));
    }

    /**
     * Find a report by ID.
     */
    public function findReport(int|string $id): ?object
    {
        try {
            $real = EmergencyReport::find($id);
            if ($real) {
                return $this->decorateReport($real, 0);
            }
        } catch (Throwable $e) {
            // Graceful fallback
        }

        return $this->getDefaultDemoReports()->firstWhere('id', (int) $id)
            ?? $this->getDefaultDemoReports()->first();
    }

    /**
     * Decorate a real EmergencyReport model instance with multimodal placeholders.
     */
    protected function decorateReport(EmergencyReport $report, int $index): object
    {
        $types = ['Fire & Smoke', 'Structural Collapse', 'Flash Flood', 'Traffic Collision', 'Medical Emergency'];
        $risks = ['Critical', 'High', 'Medium', 'Low'];
        $type = $types[$index % count($types)];
        $risk = $risks[$index % count($risks)];

        $status = ucfirst($report->status ?? 'Received');
        if (strtolower($status) === 'pending') {
            $status = 'Received';
        }

        return (object) [
            'id' => $report->id,
            'description' => $report->description,
            'image' => $report->image,
            'latitude' => (float) $report->latitude,
            'longitude' => (float) $report->longitude,
            'status' => $status,
            'created_at' => $report->created_at ?? Carbon::now()->subMinutes(15),
            'formatted_date' => optional($report->created_at)->format('M d, Y - H:i') ?? Carbon::now()->format('M d, Y - H:i'),
            'time_ago' => optional($report->created_at)->diffForHumans() ?? 'Just now',
            'emergency_type' => $type,
            'risk_level' => $risk,
            'final_score' => number_format(9.2 - ($index * 1.3), 1),
            'confidence' => 95 - ($index * 4),
            'final_decision' => $risk === 'Critical' ? 'Immediate Dispatch Required' : ($risk === 'High' ? 'High Priority Dispatch' : 'Standard Response Protocol'),
            'location_name' => 'Reported Coordinates (' . round($report->latitude, 4) . ', ' . round($report->longitude, 4) . ')',
            'citizen_name' => 'Verified Citizen',
            'text_analysis' => [
                'urgency_score' => '9.1 / 10',
                'confidence' => '94%',
                'extracted_info' => 'Identified hazard: ' . substr($report->description, 0, 100) . '...'
            ],
            'image_analysis' => [
                'detected_type' => $report->image ? 'Visual Hazard Verified' : 'No Image Attached',
                'confidence' => $report->image ? '91%' : 'N/A',
                'image_info' => $report->image ? 'Image analyzed. High priority visual matches detected.' : 'Text-only report submitted.'
            ],
            'fusion_decision' => [
                'final_type' => $type,
                'final_score' => '9.2 / 10',
                'risk_level' => $risk,
                'final_decision' => 'Dispatch Emergency Units Immediately',
                'recommended_units' => '2 Fire Units, 1 EMS Ambulance, 1 Patrol Cruiser'
            ],
            'geospatial_info' => [
                'nearest_station' => 'Station #12 - Central District Response',
                'estimated_eta' => '5 - 8 mins',
                'route' => 'Main Highway Route - Clear Traffic'
            ],
        ];
    }

    /**
     * Get default structured demo reports.
     */
    public function getDefaultDemoReports(): Collection
    {
        return collect([
            (object) [
                'id' => 1042,
                'description' => 'Heavy black smoke and large flames rising from the 3rd floor commercial warehouse. Multiple personnel evacuating, possibility of trapped maintenance workers.',
                'image' => null,
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'status' => 'In Progress',
                'created_at' => Carbon::now()->subMinutes(12),
                'formatted_date' => Carbon::now()->subMinutes(12)->format('M d, Y - H:i'),
                'time_ago' => '12 minutes ago',
                'emergency_type' => 'Fire & Smoke',
                'risk_level' => 'Critical',
                'final_score' => '9.6',
                'confidence' => 97,
                'final_decision' => 'Immediate Dispatch Required',
                'location_name' => 'Industrial Zone 3, Al-Kharj Road, Riyadh',
                'citizen_name' => 'Fahad Al-Otaibi',
                'text_analysis' => [
                    'urgency_score' => '9.8 / 10',
                    'confidence' => '98%',
                    'extracted_info' => 'Commercial warehouse fire, 3rd floor source, visible heavy black smoke, human presence reported.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Industrial Fire & Plume Hazard',
                    'confidence' => '96%',
                    'image_info' => 'Dense black smoke column detected, high-temperature thermal signature.'
                ],
                'fusion_decision' => [
                    'final_type' => 'Major Industrial Fire with Life Safety Threat',
                    'final_score' => '9.6 / 10',
                    'risk_level' => 'Critical',
                    'final_decision' => 'Priority 1 Full Alarm: Rapid Structure Fire Rescue Response',
                    'recommended_units' => '3 Pumper Trucks, 1 Ladder Truck, 2 Heavy Rescue Units, 2 Ambulances'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Civil Defense Station #19 (2.1 km)',
                    'estimated_eta' => '4 - 6 mins',
                    'route' => 'Fastest via South Ring Rd Exit 18'
                ],
            ],
            (object) [
                'id' => 1041,
                'description' => 'Multi-vehicle collision involving two passenger cars and a delivery van. Fuel is leaking onto the roadway. Two drivers injured and unable to exit.',
                'image' => null,
                'latitude' => 24.7458,
                'longitude' => 46.6521,
                'status' => 'Assigned',
                'created_at' => Carbon::now()->subMinutes(34),
                'formatted_date' => Carbon::now()->subMinutes(34)->format('M d, Y - H:i'),
                'time_ago' => '34 minutes ago',
                'emergency_type' => 'Traffic Collision',
                'risk_level' => 'High',
                'final_score' => '8.4',
                'confidence' => 92,
                'final_decision' => 'High Priority Dispatch',
                'location_name' => 'King Fahd Rd & Tahlia St Junction, Riyadh',
                'citizen_name' => 'Sarah Al-Mansoor',
                'text_analysis' => [
                    'urgency_score' => '8.6 / 10',
                    'confidence' => '94%',
                    'extracted_info' => '3 vehicles, fuel leak, road blockage, trapped occupants.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Severe Vehicle Impact & Fuel Spill',
                    'confidence' => '90%',
                    'image_info' => 'Deformation on vehicle chassis, liquid spread pattern on asphalt.'
                ],
                'fusion_decision' => [
                    'final_type' => 'High-Speed Collision with Hazmat & Entrapment',
                    'final_score' => '8.4 / 10',
                    'risk_level' => 'High',
                    'final_decision' => 'Dispatch Traffic Police, Extrication Unit, and Paramedics',
                    'recommended_units' => '1 Heavy Extrication Rescue, 2 Ambulances, 2 Traffic Patrol Units'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Central Emergency Center #4 (3.4 km)',
                    'estimated_eta' => '7 - 9 mins',
                    'route' => 'Via King Fahd Service Rd'
                ],
            ],
            (object) [
                'id' => 1040,
                'description' => 'Sudden flash flooding in underpass following torrential rain. Water level reached windshield of stranded sedan with family inside.',
                'image' => null,
                'latitude' => 24.7891,
                'longitude' => 46.7214,
                'status' => 'In Progress',
                'created_at' => Carbon::now()->subHours(1)->subMinutes(15),
                'formatted_date' => Carbon::now()->subHours(1)->subMinutes(15)->format('M d, Y - H:i'),
                'time_ago' => '1 hour ago',
                'emergency_type' => 'Flash Flood',
                'risk_level' => 'High',
                'final_score' => '8.1',
                'confidence' => 89,
                'final_decision' => 'High Priority Dispatch',
                'location_name' => 'Northern Ring Road Underpass, Riyadh',
                'citizen_name' => 'Khalid Al-Ghamdi',
                'text_analysis' => [
                    'urgency_score' => '8.2 / 10',
                    'confidence' => '91%',
                    'extracted_info' => 'Rising floodwaters, submerged vehicle, trapped passengers.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Deep Water Accumulation & Submerged Vehicle',
                    'confidence' => '88%',
                    'image_info' => 'Water level above tire line, flow velocity moderate.'
                ],
                'fusion_decision' => [
                    'final_type' => 'Severe Road Inundation & Rescue Needed',
                    'final_score' => '8.1 / 10',
                    'risk_level' => 'High',
                    'final_decision' => 'Deploy Water Rescue Team & Close Underpass Access',
                    'recommended_units' => '1 Water Rescue Boat Unit, 1 High-Clearance Rescue Truck, 1 Police Unit'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Civil Defense Water Rescue Unit #8 (4.2 km)',
                    'estimated_eta' => '8 - 11 mins',
                    'route' => 'Via Northern Ring Rd'
                ],
            ],
            (object) [
                'id' => 1039,
                'description' => 'Partial collapse of an exterior masonry balcony on an older 2-story residential building. Debris fallen on sidewalk, crack propagation visible.',
                'image' => null,
                'latitude' => 24.6341,
                'longitude' => 46.7112,
                'status' => 'Analyzing',
                'created_at' => Carbon::now()->subHours(2)->subMinutes(20),
                'formatted_date' => Carbon::now()->subHours(2)->subMinutes(20)->format('M d, Y - H:i'),
                'time_ago' => '2 hours ago',
                'emergency_type' => 'Structural Collapse',
                'risk_level' => 'Medium',
                'final_score' => '6.4',
                'confidence' => 85,
                'final_decision' => 'Standard Dispatch',
                'location_name' => 'Al-Bat’ha Historical District, Old City',
                'citizen_name' => 'Nasser Al-Subaie',
                'text_analysis' => [
                    'urgency_score' => '6.5 / 10',
                    'confidence' => '87%',
                    'extracted_info' => 'Balcony collapse, masonry rubble on sidewalk, no immediate casualties reported.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Masonry Structural Failure',
                    'confidence' => '83%',
                    'image_info' => 'Detached concrete slab, debris field confined to pedestrian walkway.'
                ],
                'fusion_decision' => [
                    'final_type' => 'Non-Catastrophic Structural Hazard',
                    'final_score' => '6.4 / 10',
                    'risk_level' => 'Medium',
                    'final_decision' => 'Cordon Off Perimeter & Dispatch Structural Engineer Inspection',
                    'recommended_units' => '1 Municipality Safety Inspector, 1 Police Cordon Patrol'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Municipal Safety Bureau #2 (1.5 km)',
                    'estimated_eta' => '6 - 8 mins',
                    'route' => 'Via Batha St'
                ],
            ],
            (object) [
                'id' => 1038,
                'description' => 'Elderly individual collapsed on park promenade with severe breathing distress and unconsciousness. Bystanders attempting CPR.',
                'image' => null,
                'latitude' => 24.6892,
                'longitude' => 46.6845,
                'status' => 'Resolved',
                'created_at' => Carbon::now()->subHours(4),
                'formatted_date' => Carbon::now()->subHours(4)->format('M d, Y - H:i'),
                'time_ago' => '4 hours ago',
                'emergency_type' => 'Medical Emergency',
                'risk_level' => 'Critical',
                'final_score' => '9.2',
                'confidence' => 96,
                'final_decision' => 'Immediate Dispatch Required',
                'location_name' => 'King Abdullah Park Promenade, Malaz, Riyadh',
                'citizen_name' => 'Reem Al-Zahrani',
                'text_analysis' => [
                    'urgency_score' => '9.5 / 10',
                    'confidence' => '97%',
                    'extracted_info' => 'Cardiac arrest symptoms, unconsciousness, bystander CPR initiated.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Medical Incident in Public Space',
                    'confidence' => '94%',
                    'image_info' => 'Individual supine on ground, gathering crowd.'
                ],
                'fusion_decision' => [
                    'final_type' => 'Out-of-Hospital Cardiac Arrest Priority',
                    'final_score' => '9.2 / 10',
                    'risk_level' => 'Critical',
                    'final_decision' => 'Dispatch Red Crescent ALS Ambulance with Defibrillator',
                    'recommended_units' => '1 Red Crescent ALS Unit, 1 Park Security AED Carrier'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Red Crescent Station #3 (1.2 km)',
                    'estimated_eta' => '3 - 5 mins',
                    'route' => 'Park South Gate Entrance'
                ],
            ],
            (object) [
                'id' => 1037,
                'description' => 'Pungent chemical odor detected near industrial paint storage shed. Minor yellow haze visible near rear ventilation shaft.',
                'image' => null,
                'latitude' => 24.6023,
                'longitude' => 46.7532,
                'status' => 'Received',
                'created_at' => Carbon::now()->subHours(5)->subMinutes(40),
                'formatted_date' => Carbon::now()->subHours(5)->subMinutes(40)->format('M d, Y - H:i'),
                'time_ago' => '5 hours ago',
                'emergency_type' => 'Hazardous Material',
                'risk_level' => 'Medium',
                'final_score' => '5.8',
                'confidence' => 84,
                'final_decision' => 'Standard Dispatch',
                'location_name' => 'Second Industrial City, Street 45, Riyadh',
                'citizen_name' => 'Tariq Al-Harbi',
                'text_analysis' => [
                    'urgency_score' => '6.0 / 10',
                    'confidence' => '85%',
                    'extracted_info' => 'Chemical smell, solvent vapor risk, confined to warehouse perimeter.'
                ],
                'image_analysis' => [
                    'detected_type' => 'Industrial Storage Facility',
                    'confidence' => '83%',
                    'image_info' => 'Ventilation exhaust with localized haze.'
                ],
                'fusion_decision' => [
                    'final_type' => 'Potential Chemical Vapor Release',
                    'final_score' => '5.8 / 10',
                    'risk_level' => 'Medium',
                    'final_decision' => 'Hazmat Reconnaissance Team Dispatched',
                    'recommended_units' => '1 Hazmat Detection Unit, 1 Safety Patrol'
                ],
                'geospatial_info' => [
                    'nearest_station' => 'Civil Defense Hazmat Unit #22 (5.0 km)',
                    'estimated_eta' => '10 - 12 mins',
                    'route' => 'Industrial Arterial 2'
                ],
            ],
        ]);
    }

    /**
     * Get operational statistics for responder dashboard.
     */
    public function getResponderStats(): array
    {
        $reports = $this->getReports();

        return [
            'active_emergencies' => $reports->whereIn('status', ['Received', 'Analyzing', 'Assigned', 'In Progress'])->count(),
            'critical' => $reports->where('risk_level', 'Critical')->count(),
            'high_risk' => $reports->where('risk_level', 'High')->count(),
            'pending_analysis' => $reports->where('status', 'Analyzing')->count() + $reports->where('status', 'Received')->count(),
            'resolved_today' => $reports->where('status', 'Resolved')->count() + 8,
        ];
    }

    /**
     * Get citizen dashboard stats.
     */
    public function getCitizenStats(): array
    {
        $reports = $this->getReports();

        return [
            'total_reports' => $reports->count(),
            'pending' => $reports->whereIn('status', ['Received', 'Analyzing'])->count(),
            'in_progress' => $reports->whereIn('status', ['Assigned', 'In Progress'])->count(),
            'resolved' => $reports->where('status', 'Resolved')->count(),
        ];
    }
}
