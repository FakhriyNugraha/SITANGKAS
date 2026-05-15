<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knn_training_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_code')->unique();
            $table->integer('phishing_score');
            $table->integer('otp_score');
            $table->integer('password_score');
            $table->integer('marketplace_score');
            $table->integer('pinjol_score');
            $table->integer('wrong_count');
            $table->integer('avg_time_seconds');
            $table->integer('help_opened_count');
            $table->enum('awareness_level', ['beginner', 'intermediate', 'advanced']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knn_training_profiles');
    }
};
