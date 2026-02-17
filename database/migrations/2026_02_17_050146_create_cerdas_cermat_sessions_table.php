<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cerdas_cermat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->string('name_1');
            $table->string('name_2');
            $table->string('name_3');
            $table->integer('current_round')->default(1);
            $table->double('score_round_1')->default(0);
            $table->double('score_round_2')->default(0);
            $table->double('score_round_3')->default(0);
            $table->string('status')->default('registered'); // registered, round_1_done, round_2_qualified, round_2_done, round_3_qualified, finished
            $table->timestamp('start_time_round_1')->nullable();
            $table->timestamp('end_time_round_1')->nullable();
            $table->timestamp('start_time_round_2')->nullable();
            $table->timestamp('end_time_round_2')->nullable();
            $table->timestamp('start_time_round_3')->nullable();
            $table->timestamp('end_time_round_3')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cerdas_cermat_sessions');
    }
};
