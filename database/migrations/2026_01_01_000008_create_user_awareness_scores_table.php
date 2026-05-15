<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_awareness_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('simulation_sessions')->cascadeOnDelete();
            $table->decimal('phishing_score', 6, 2)->default(0);
            $table->decimal('otp_score', 6, 2)->default(0);
            $table->decimal('password_score', 6, 2)->default(0);
            $table->decimal('marketplace_score', 6, 2)->default(0);
            $table->decimal('pinjol_score', 6, 2)->default(0);
            $table->integer('wrong_count')->default(0);
            $table->decimal('avg_time_seconds', 8, 2)->default(0);
            $table->integer('help_opened_count')->default(0);
            $table->enum('awareness_level', ['beginner', 'intermediate', 'advanced']);
            $table->json('knn_neighbors')->nullable();
            $table->json('category_scores')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_awareness_scores');
    }
};
