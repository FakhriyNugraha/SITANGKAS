<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuzzy_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('normal_indicator');
            $table->string('keyword_variation');
            $table->string('relevant_category')->nullable();
            $table->integer('risk_weight')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('normal_indicator');
            $table->index('relevant_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuzzy_indicators');
    }
};
