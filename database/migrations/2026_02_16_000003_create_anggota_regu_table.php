<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Anggota regu 8-10 orang per regu.
     */
    public function up(): void
    {
        Schema::create('anggota_regu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->string('nama');
            $table->unsignedTinyInteger('nomor_punggung')->nullable();
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_regu');
    }
};
