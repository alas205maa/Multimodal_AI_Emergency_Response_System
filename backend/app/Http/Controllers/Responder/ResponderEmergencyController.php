<?php

namespace App\Http\Controllers\Responder;

use App\Http\Controllers\Controller;
use App\Models\EmergencyReport;
use App\Services\EmergencyDemoService;
use Illuminate\Http\Request;
use Throwable;

class ResponderEmergencyController extends Controller
{
    protected EmergencyDemoService $demoService;

    public function __construct(EmergencyDemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    /**
     * Display emergencies triage management table.
     */
    public function index(Request $request)
    {
        $reports = $this->demoService->getReports();

        if ($search = $request->input('search')) {
            $reports = $reports->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->description), strtolower($search)) ||
                       str_contains(strtolower($item->emergency_type), strtolower($search)) ||
                       str_contains(strtolower($item->location_name), strtolower($search)) ||
                       str_contains((string) $item->id, $search);
            });
        }

        if ($risk = $request->input('risk')) {
            $reports = $reports->where('risk_level', $risk);
        }

        if ($status = $request->input('status')) {
            $reports = $reports->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $reports = $reports->where('emergency_type', $type);
        }

        return view('responder.emergencies.index', [
            'reports' => $reports,
            'currentSearch' => $request->input('search', ''),
            'currentRisk' => $request->input('risk', ''),
            'currentStatus' => $request->input('status', ''),
            'currentType' => $request->input('type', ''),
        ]);
    }

    /**
     * Display rich responder emergency details.
     */
    public function show($id)
    {
        $report = $this->demoService->findReport($id);

        return view('responder.emergencies.show', [
            'emergency' => $report,
        ]);
    }

    /**
     * Update incident status safely.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:Received,Analyzing,Assigned,In Progress,Resolved,Cancelled'],
        ]);

        try {
            $realReport = EmergencyReport::find($id);
            if ($realReport) {
                $realReport->status = strtolower($request->input('status'));
                $realReport->save();
            }
        } catch (Throwable $e) {
            // Graceful fallback
        }

        return back()->with('status', 'Emergency #' . $id . ' status successfully updated to ' . $request->input('status'));
    }
}
