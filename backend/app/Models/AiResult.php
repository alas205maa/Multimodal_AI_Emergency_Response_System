<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiResult extends Model
{
    use HasFactory;

    protected $table = 'ai_results';

    protected $fillable = [
        'emergency_report_id',
        'text_urgency',
        'emergency_type',
        'image_type',
        'final_type',
        'response_type',
        'risk_score',
        'priority',
        'confidence',
        'entities',
    ];

    protected function casts(): array
    {
        return [
            'text_urgency' => 'integer',
            'risk_score' => 'integer',
            'confidence' => 'decimal:4',
            'entities' => 'array',
        ];
    }

    public function emergencyReport(): BelongsTo
    {
        return $this->belongsTo(
            EmergencyReport::class,
            'emergency_report_id'
        );
    }
}