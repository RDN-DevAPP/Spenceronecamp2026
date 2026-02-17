<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cerdas_cermat_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('cerdas_cermat_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('cerdas_cermat_questions')->onDelete('cascade');
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->double('score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cerdas_cermat_answers');
    }
};
