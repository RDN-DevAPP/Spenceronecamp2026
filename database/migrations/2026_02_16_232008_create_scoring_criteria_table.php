<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Kriteria penilaian untuk setiap mata lomba.
     */
    public function up(): void
    {
        Schema::create('scoring_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_lomba_id')->constrained('mata_lomba')->cascadeOnDelete();
            $table->string('nama_kriteria');
            $table->decimal('nilai_min', 5, 2)->comment('Nilai minimum untuk kriteria ini');
            $table->decimal('nilai_max', 5, 2)->comment('Nilai maksimum untuk kriteria ini');
            $table->integer('urutan')->default(0)->comment('Urutan tampilan kriteria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scoring_criteria');
    }
};
