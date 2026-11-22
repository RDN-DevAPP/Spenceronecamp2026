<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sertifikat_peserta')) {
            Schema::create('sertifikat_peserta', function (Blueprint $table) {
                $table->id();
                $table->foreignId('anggota_regu_id')->constrained('anggota_regu')->onDelete('cascade');
                $table->string('nomor_sertifikat', 150);
                $table->unsignedInteger('nomor_urut')->default(1);
                $table->string('verify_token', 64)->unique();
                $table->date('diterbitkan_pada')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_peserta');
    }
};
