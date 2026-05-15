<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('simulation_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cyber_case_id')->constrained()->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('case_options')->nullOnDelete();
            $table->text('selected_action_text')->nullable();
            $table->text('reason_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->decimal('action_score', 6, 2)->default(0);
            $table->decimal('fuzzy_score', 6, 2)->default(0);
            $table->decimal('case_score', 6, 2)->default(0);
            $table->json('detected_indicators')->nullable();
            $table->json('missed_indicators')->nullable();
            $table->integer('answer_time_seconds')->default(0);
            $table->boolean('help_opened')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
