<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_reports', function (Blueprint $table) {
            $table->string('emergency_id', 20)
                ->unique()
                ->nullable()
                ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('emergency_reports', function (Blueprint $table) {
            $table->dropUnique(['emergency_id']);
            $table->dropColumn('emergency_id');
        });
    }
};