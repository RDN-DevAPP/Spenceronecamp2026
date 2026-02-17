<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Detail nilai per kriteria untuk setiap score.
     */
    public function up(): void
    {
        Schema::create('score_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->constrained('scores')->cascadeOnDelete();
            $table->foreignId('scoring_criteria_id')->constrained('scoring_criteria')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->comment('Nilai untuk kriteria ini');
            $table->timestamps();

            $table->unique(['score_id', 'scoring_criteria_id'], 'score_criteria_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_details');
    }
};
