<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use App\Services\EmergencyDemoService;
use Illuminate\Http\Request;

class ResponderAnalyticsController extends Controller
{
    protected EmergencyDemoService $demoService;

    public function __construct(EmergencyDemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Display the analytics and reporting dashboard.
     */
    public function index(Request $request)
    {
        $reports = $this->demoService->getReports();

        $byType = [
            'Fire & Smoke' => 38,
            'Traffic Collision' => 27,
            'Medical Emergency' => 19,
            'Flash Flood' => 10,
            'Structural Collapse' => 6,
        ];

        $byRisk = [
            'Critical' => 24,
            'High' => 36,
            'Medium' => 28,
            'Low' => 12,
        ];

        $byStatus = [
            'Received' => 14,
            'Analyzing' => 9,
            'Assigned' => 18,
            'In Progress' => 22,
            'Resolved' => 37,
        ];

        $monthlyTrend = [
            ['month' => 'May', 'count' => 142],
            ['month' => 'Jun', 'count' => 178],
            ['month' => 'Jul', 'count' => 195],
            ['month' => 'Aug', 'count' => 220],
            ['month' => 'Sep', 'count' => 164],
        ];

        return view('responder.analytics', [
            'reports' => $reports,
            'byType' => $byType,
            'byRisk' => $byRisk,
            'byStatus' => $byStatus,
            'monthlyTrend' => $monthlyTrend,
            'avgResponseTime' => '6.4 mins',
            'resolutionRate' => '94.2%',
            'aiConfidenceAvg' => '91.8%',
        ]);
    }
}
