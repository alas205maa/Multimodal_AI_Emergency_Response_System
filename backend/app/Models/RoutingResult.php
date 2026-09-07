<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutingResult extends Model
{
    use HasFactory;

    protected $table = 'routing_results';

    protected $fillable = [
        'emergency_report_id',
        'resource_available',
        'resource_id',
        'resource_type',
        'resource_name',
        'resource_latitude',
        'resource_longitude',
        'route',
        'distance_km',
        'estimated_time_minutes',
    ];

    protected function casts(): array
    {
        return [
            'resource_available' => 'boolean',
            'resource_latitude' => 'decimal:8',
            'resource_longitude' => 'decimal:8',
            'route' => 'array',
            'distance_km' => 'decimal:2',
            'estimated_time_minutes' => 'integer',
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