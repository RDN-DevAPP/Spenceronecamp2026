<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Satu User (role=regu) punya satu ReguProfile.
     */
    public function up(): void
    {
        Schema::create('regu_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama_regu');
            $table->string('jenis', 10); // 'putra' | 'putri'
            $table->unsignedTinyInteger('nomor_regu')->comment('1-4 putra, 1-4 putri');
            $table->string('poster_digital_path')->nullable();
            $table->timestamps();

            $table->unique(['jenis', 'nomor_regu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regu_profiles');
    }
};
