<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RoutingService
{
    public function analyze(
        string $emergencyId,
        string $responseType,
        array $location
    ): array {
        $response = Http::post(
            config('services.ai.routing_url') . '/routing/route',
            [
                'emergency_id' => $emergencyId,
                'response_type' => $responseType,
                'location' => [
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                ],
            ]
        );

        $response->throw();

        return $response->json();
    }
}