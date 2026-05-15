<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simulation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('mode', ['category', 'mixed', 'recommended'])->default('category');
            $table->string('selected_category')->nullable();
            $table->integer('total_cases')->default(10);
            $table->integer('completed_cases')->default(0);
            $table->decimal('total_score', 6, 2)->default(0);
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->json('case_order')->nullable();
            $table->integer('current_index')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_sessions');
    }
};
