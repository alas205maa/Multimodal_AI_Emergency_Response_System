<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use App\Services\EmergencyDemoService;
use Carbon\Carbon;

class ResponderDashboardController extends Controller
{
    protected EmergencyDemoService $demoService;

    public function __construct(EmergencyDemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Display Emergency Operations Dashboard.
     */
    public function index()
    {
        $stats = $this->demoService->getResponderStats();
        $reports = $this->demoService->getReports();
        $criticalIncident = $reports->firstWhere('risk_level', 'Critical');
        $latestEmergencies = $reports->take(6);

        return view('responder.dashboard', [
            'stats' => $stats,
            'criticalIncident' => $criticalIncident,
            'latestEmergencies' => $latestEmergencies,
            'currentDate' => Carbon::now()->format('l, F j, Y'),
            'currentTime' => Carbon::now()->format('H:i:s T'),
            'allReports' => $reports,
        ]);
    }
}
