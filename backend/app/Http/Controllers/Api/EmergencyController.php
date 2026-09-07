<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\RoutingService;
use App\Services\EmergencyOrchestrator;
class EmergencyController extends Controller
{
    public function testOrchestrator(
        EmergencyOrchestrator $orchestrator
        ): JsonResponse {
        $report = EmergencyReport::create([
            'emergency_id' => 'EMG-ORCH-TEST-'.uniqid(),
            'description' => 'Test fire emergency',
            'image' => null,
            'latitude' => 15.3694,
            'longitude' => 44.1910,
            'status' => 'pending',
        ]);

        $visionResult = [
            // 'emergency_id' => 'EMG-ORCH-TEST',
            'image_type' => 'Fire',
            'confidence' => 0.94,
        ];

        $result = $orchestrator->process(
            $report,
            null,
            $visionResult
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function testRouting(RoutingService $routingService): JsonResponse
    {
        $result = $routingService->analyze(
            'EMG-ROUTING-TEST',
            'Fire_Rescue',
            [
                'latitude' => 15.3694,
                'longitude' => 44.1910,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => ['nullable', 'string', 'min:5'],
            'image' => ['nullable', 'string'],
            'location' => ['required', 'array'],
            'location.latitude' => ['required', 'numeric'],
            'location.longitude' => ['required', 'numeric'],
        ]);

        if (empty($validated['text']) && empty($validated['image'])) {
            return response()->json([
                'success' => false,
                'message' => 'At least text or image is required.',
            ], 422);
        }

        $report = EmergencyReport::create([
            'description' => $validated['text'] ?? null,
            'image' => $validated['image'] ?? null,
            'latitude' => $validated['location']['latitude'],
            'longitude' => $validated['location']['longitude'],
            'status' => 'pending',
        ]);

        $emergencyId = 'EMG-' . str_pad(
            $report->id,
            6,
            '0',
            STR_PAD_LEFT
        );

        $report->update([
            'emergency_id' => $emergencyId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Emergency created successfully.',
            'data' => [
                'emergency_id' => $emergencyId,
                'status' => $report->status,
                'location' => [
                    'latitude' => $report->latitude,
                    'longitude' => $report->longitude,
                ],
            ],
        ], 201);
    }
}