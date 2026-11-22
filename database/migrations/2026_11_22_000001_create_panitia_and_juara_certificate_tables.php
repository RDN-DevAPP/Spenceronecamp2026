<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('panitia_members')) {
            Schema::create('panitia_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();
                $table->string('nama', 150);
                $table->string('jabatan', 100);
                $table->string('divisi', 100)->nullable();
                $table->string('asal_instansi', 150)->nullable();
                $table->string('no_hp', 30)->nullable();
                $table->string('foto_path', 255)->nullable();
                $table->string('nomor_sertifikat', 150)->nullable();
                $table->unsignedInteger('nomor_urut')->nullable();
                $table->string('verify_token', 64)->nullable()->unique();
                $table->date('diterbitkan_pada')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sertifikat_juaras')) {
            Schema::create('sertifikat_juaras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('season_id')->nullable()->constrained('seasons')->nullOnDelete();
                $table->foreignId('mata_lomba_id')->nullable()->constrained('mata_lomba')->nullOnDelete();
                $table->foreignId('regu_profile_id')->nullable()->constrained('regu_profiles')->nullOnDelete();
                $table->foreignId('anggota_regu_id')->nullable()->constrained('anggota_regu')->nullOnDelete();
                $table->string('nama_penerima', 180);
                $table->string('nama_lomba', 150);
                $table->string('kategori', 50)->nullable();
                $table->string('peringkat', 80);
                $table->string('mode', 30)->default('regu');
                $table->string('nomor_sertifikat', 150);
                $table->unsignedInteger('nomor_urut')->default(1);
                $table->string('verify_token', 64)->unique();
                $table->date('diterbitkan_pada')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikat_juaras');
        Schema::dropIfExists('panitia_members');
    }
};
