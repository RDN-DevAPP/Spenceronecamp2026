<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cerdas_cermat_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_lomba_id')->constrained('mata_lomba')->onDelete('cascade');
            $table->string('type'); // 'Pilihan Ganda', 'Isian Singkat', 'Uraian'
            $table->text('question');
            $table->json('options')->nullable(); // For multiple choice
            $table->text('correct_answer');
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cerdas_cermat_questions');
    }
};
