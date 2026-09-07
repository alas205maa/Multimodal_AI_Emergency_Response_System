<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Services\EmergencyDemoService;
use Illuminate\Http\Request;

class CitizenDashboardController extends Controller
{
    protected EmergencyDemoService $demoService;

    public function __construct(EmergencyDemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Display the citizen dashboard.
     */
    public function index()
    {
        $stats = $this->demoService->getCitizenStats();
        $recentReports = $this->demoService->getReports()->take(5);

        return view('citizen.dashboard', [
            'stats' => $stats,
            'recentReports' => $recentReports,
            'userName' => auth()->user()->name ?? 'Citizen Member',
        ]);
    }
}
