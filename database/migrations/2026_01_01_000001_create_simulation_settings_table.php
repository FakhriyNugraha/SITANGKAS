<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('default_case_count')->default(10);
            $table->integer('fuzzy_match_threshold')->default(70);
            $table->integer('fuzzy_partial_threshold')->default(60);
            $table->integer('knn_k_value')->default(3);
            $table->boolean('is_mixed_mode_enabled')->default(true);
            $table->boolean('randomize_cases')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_settings');
    }
};
