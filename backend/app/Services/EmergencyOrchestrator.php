<?php

namespace App\Services;

use App\Models\AiResult;
use App\Models\EmergencyReport;
use App\Models\RoutingResult;

class EmergencyOrchestrator
{
    public function __construct(
        private DecisionService $decisionService,
        private RoutingService $routingService
    ) {
    }

    public function process(
        EmergencyReport $report,
        ?array $nlpResult = null,
        ?array $visionResult = null
    ): array {
        $location = [
            'latitude' => (float) $report->latitude,
            'longitude' => (float) $report->longitude,
        ];

        /*
         * Step 1: Decision / Fusion
         */
        $decisionResult = $this->decisionService->analyze(
            $report->emergency_id,
            $nlpResult,
            $visionResult,
            $location
        );

        /*
         * Step 2: Save AI / Decision result
         */
        $aiResult = AiResult::updateOrCreate(
            [
                'emergency_report_id' => $report->id,
            ],
            [
                'text_urgency' => $nlpResult['text_urgency'] ?? null,
                'emergency_type' => $nlpResult['emergency_type'] ?? null,
                'image_type' => $visionResult['image_type'] ?? null,

                'final_type' => $decisionResult['final_type'] ?? null,
                'response_type' => $decisionResult['response_type'] ?? null,
                'risk_score' => $decisionResult['risk_score'] ?? null,
                'priority' => $decisionResult['priority'] ?? null,
                'confidence' => $decisionResult['confidence'] ?? null,

                'entities' => $nlpResult['entities'] ?? null,
            ]
        );

        /*
         * Step 3: Normal case
         *
         * Routing MUST NOT be called.
         */
        if (($decisionResult['final_type'] ?? null) === 'Normal') {
            $report->update([
                'status' => 'completed',
            ]);

            return [
                'emergency_id' => $report->emergency_id,
                'status' => 'completed',
                'decision' => $decisionResult,
                'routing' => null,
            ];
        }

        /*
         * Step 4: Emergency case
         *
         * Routing is required.
         */
        $routingResult = $this->routingService->analyze(
            $report->emergency_id,
            $decisionResult['response_type'],
            $location
        );

        /*
         * Step 5: Save routing result
         */
        RoutingResult::updateOrCreate(
            [
                'emergency_report_id' => $report->id,
            ],
            [
                'resource_available' =>
                    $routingResult['resource_available'] ?? false,

                'resource_id' =>
                    $routingResult['resource']['id'] ?? null,

                'resource_type' =>
                    $routingResult['resource']['type'] ?? null,

                'resource_name' =>
                    $routingResult['resource']['name'] ?? null,

                'resource_latitude' =>
                    $routingResult['resource']['latitude'] ?? null,

                'resource_longitude' =>
                    $routingResult['resource']['longitude'] ?? null,

                'route' =>
                    $routingResult['route'] ?? null,

                'distance_km' =>
                    $routingResult['distance_km'] ?? null,

                'estimated_time_minutes' =>
                    $routingResult['estimated_time_minutes'] ?? null,
            ]
        );

        /*
         * Step 6: Update emergency status
         */
        $report->update([
            'status' => 'completed',
        ]);

        return [
            'emergency_id' => $report->emergency_id,
            'status' => 'completed',
            'decision' => $decisionResult,
            'routing' => $routingResult,
        ];
    }
}