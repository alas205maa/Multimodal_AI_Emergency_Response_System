<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyReport;
use App\Services\EmergencyOrchestrator;
use App\Services\NlpService;
use App\Services\VisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function __construct(
        private EmergencyOrchestrator $orchestrator,
        private VisionService $visionService,
        private NlpService $nlpService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'nullable|string|min:5',
            'image' => 'nullable|string',
            'location.latitude' => 'required|numeric',
            'location.longitude' => 'required|numeric',
        ]);

        // At least text or image must be provided
        if (
            empty($validated['text']) &&
            empty($validated['image'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'At least text or image is required.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Create Emergency Report
        |--------------------------------------------------------------------------
        */

        $report = EmergencyReport::create([
            'emergency_id' => 'TEMP',

            'description' => $validated['text'] ?? null,

            'image' => $validated['image'] ?? null,

            'latitude' => $validated['location']['latitude'],

            'longitude' => $validated['location']['longitude'],

            'status' => 'processing',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. Generate final emergency_id
        |--------------------------------------------------------------------------
        */

        $report->update([
            'emergency_id' => 'EMG-' . str_pad(
                $report->id,
                6,
                '0',
                STR_PAD_LEFT
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. NLP
        |--------------------------------------------------------------------------
        */

        $nlpResult = null;

        if (!empty($validated['text'])) {

            $nlpResult = $this->nlpService->analyze(
                $report->emergency_id,
                $validated['text']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Vision
        |--------------------------------------------------------------------------
        */

        $visionResult = null;

        if (!empty($validated['image'])) {

            $visionResult = $this->visionService->analyze(
                $report->emergency_id,
                $validated['image']
            );

            // Decision Engine accepts only these fields
            $visionResult = [
                'image_type' => $visionResult['image_type'] ?? null,
                'confidence' => $visionResult['confidence'] ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Run Decision + Routing through Orchestrator
        |--------------------------------------------------------------------------
        */

        $result = $this->orchestrator->process(
            $report,
            $nlpResult,
            $visionResult
        );

        /*
        |--------------------------------------------------------------------------
        | 6. Return final response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 201);
    }

    public function testRouting(): JsonResponse
    {
        // Keep your existing testRouting() here
    }

    public function testOrchestrator(
        EmergencyOrchestrator $orchestrator
    ): JsonResponse {
        // Keep your existing testOrchestrator() here
    }
}
