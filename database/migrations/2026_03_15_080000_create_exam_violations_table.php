<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('cerdas_cermat_sessions')->onDelete('cascade');
            $table->foreignId('regu_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->enum('violation_type', [
                'tab_switch',
                'copy',
                'paste',
                'cut',
                'context_menu',
                'screenshot',
                'devtools',
                'other',
            ]);
            $table->tinyInteger('round')->default(1)->comment('1, 2, or 3');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_violations');
    }
};
