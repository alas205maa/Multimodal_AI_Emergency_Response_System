<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmergencyReport extends Model
{
    use HasFactory;

    protected $table = 'emergency_reports';

    protected $fillable = [
        'emergency_id',
        'description',
        'image',
        'latitude',
        'longitude',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    public function aiResult(): HasOne
    {
        return $this->hasOne(
            AiResult::class,
            'emergency_report_id'
        );
    }

    public function routingResult(): HasOne
    {
        return $this->hasOne(
            RoutingResult::class,
            'emergency_report_id'
        );
    }
}