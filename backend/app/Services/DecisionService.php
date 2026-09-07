<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DecisionService
{
    public function analyze(
        string $emergencyId,
        ?array $nlp,
        ?array $vision,
        array $location
    ): array {
        $response = Http::post(
            config('services.ai.decision_url') . '/decision/analyze',
            [
                'emergency_id' => $emergencyId,
                'nlp' => $nlp,
                'vision' => $vision,
                'location' => $location,
            ]
        );

        $response->throw();

        return $response->json();
    }
}