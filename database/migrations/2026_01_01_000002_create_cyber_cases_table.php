<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cyber_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_code')->unique();
            $table->string('channel');
            $table->string('category');
            $table->string('category_name');
            $table->text('scenario_text');
            $table->enum('risk_label', ['aman', 'mencurigakan', 'berbahaya']);
            $table->enum('difficulty_level', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->integer('risk_score_rule')->default(0);
            $table->json('ideal_indicators')->nullable();
            $table->text('correct_action');
            $table->text('tutor_feedback');
            $table->text('source_basis')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category');
            $table->index('risk_label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cyber_cases');
    }
};
