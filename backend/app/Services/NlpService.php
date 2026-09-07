<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NlpService
{
    public function analyze(string $emergencyId, string $text): array
    {
        $response = Http::post(
            config('services.ai.nlp_url') . '/nlp/analyze',
            [
                'emergency_id' => $emergencyId,
                'text' => $text,
            ]
        );

        $response->throw();

        return $response->json();
    }
}