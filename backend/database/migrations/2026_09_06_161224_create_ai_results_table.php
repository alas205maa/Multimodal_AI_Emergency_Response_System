<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_results', function (Blueprint $table) {
            $table->id();

            // Link to the main emergency report
            $table->foreignId('emergency_report_id')
                ->constrained('emergency_reports')
                ->cascadeOnDelete();

            // NLP result
            $table->unsignedTinyInteger('text_urgency')->nullable();
            $table->string('emergency_type')->nullable();

            // Vision result
            $table->string('image_type')->nullable();

            // Decision / Fusion result
            $table->string('final_type')->nullable();
            $table->string('response_type')->nullable();
            $table->unsignedTinyInteger('risk_score')->nullable();
            $table->string('priority')->nullable();
            $table->decimal('confidence', 5, 4)->nullable();

            // NLP extracted entities
            $table->json('entities')->nullable();

            $table->timestamps();

            // One AI result record per emergency
            $table->unique('emergency_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_results');
    }
};