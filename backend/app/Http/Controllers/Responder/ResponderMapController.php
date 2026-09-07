<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use App\Services\EmergencyDemoService;
use Illuminate\Http\Request;

class ResponderMapController extends Controller
{
    protected EmergencyDemoService $demoService;

    public function __construct(EmergencyDemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Display the 2-column live emergency map interface.
     */
    public function index(Request $request)
    {
        $reports = $this->demoService->getReports();

        if ($search = $request->input('search')) {
            $reports = $reports->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->description), strtolower($search)) ||
                       str_contains(strtolower($item->emergency_type), strtolower($search)) ||
                       str_contains((string) $item->id, $search);
            });
        }

        if ($risk = $request->input('risk')) {
            $reports = $reports->where('risk_level', $risk);
        }

        if ($status = $request->input('status')) {
            $reports = $reports->where('status', $status);
        }

        return view('responder.map', [
            'emergencies' => $reports,
            'currentSearch' => $request->input('search', ''),
            'currentRisk' => $request->input('risk', ''),
            'currentStatus' => $request->input('status', ''),
        ]);
    }
}
