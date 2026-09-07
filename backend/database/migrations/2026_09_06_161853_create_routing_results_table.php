<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routing_results', function (Blueprint $table) {
            $table->id();

            // Link to the main emergency report
            $table->foreignId('emergency_report_id')
                ->constrained('emergency_reports')
                ->cascadeOnDelete();

            // Routing availability
            $table->boolean('resource_available')->default(false);

            // Selected resource
            $table->unsignedBigInteger('resource_id')->nullable();
            $table->string('resource_type')->nullable();
            $table->string('resource_name')->nullable();

            // Resource location
            $table->decimal('resource_latitude', 10, 8)->nullable();
            $table->decimal('resource_longitude', 11, 8)->nullable();

            // Calculated route
            $table->json('route')->nullable();

            // Route information
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->unsignedInteger('estimated_time_minutes')->nullable();

            $table->timestamps();

            // One routing result per emergency
            $table->unique('emergency_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routing_results');
    }
};