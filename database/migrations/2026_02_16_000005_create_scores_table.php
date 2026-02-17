<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Nilai per regu per mata lomba. Input oleh juri (0-100).
     */
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->foreignId('mata_lomba_id')->constrained('mata_lomba')->cascadeOnDelete();
            $table->foreignId('juri_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->comment('0-100');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['regu_profile_id', 'mata_lomba_id', 'juri_id'], 'scores_regu_lomba_juri_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
