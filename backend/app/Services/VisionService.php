<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VisionService
{
    public function analyze(string $emergencyId, string $imageBase64): array
    {
        $response = Http::post(
            config('services.ai.vision_url') . '/vision/analyze',
            [
                'emergency_id' => $emergencyId,
                'image' => $imageBase64,
            ]
        );

        $response->throw();

        $result = $response->json();

        $imageType = $result['emergency_type'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Normalize Vision output to the official Decision contract
        |--------------------------------------------------------------------------
        */

        $imageType = match ($imageType) {
            'Traffic Accident' => 'Traffic_Accident',
            'No Emergency' => 'Normal',
            default => $imageType,
        };

        return [
            'emergency_id' => $result['emergency_id'],
            'image_type' => $imageType,
            'confidence' => $result['confidence'] ?? null,
            'class_probabilities' => $result['class_probabilities'] ?? [],
            'image_features' => $result['image_features'] ?? [],
        ];
    }
}