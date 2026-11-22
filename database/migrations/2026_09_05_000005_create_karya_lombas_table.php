<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('karya_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons')->cascadeOnDelete();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->string('mata_lomba_slug', 50); // hasta-karya, tapak-kemah, masak-rimba
            
            // Hasta Karya & Umum
            $table->string('judul_karya')->nullable();
            $table->enum('kategori_bahan', ['alam_lokal', 'daur_ulang', 'kombinasi'])->nullable();
            $table->text('rincian_bahan_alat')->nullable();
            $table->text('fungsi_guna')->nullable();
            $table->text('deskripsi_filosofi')->nullable();
            $table->text('resep_gizi')->nullable(); // Khusus masak rimba
            
            // File Paths
            $table->string('foto_utama_path')->nullable(); // Tampak Depan / Hidangan Utama / Layout Tenda
            $table->string('foto_detail_path')->nullable(); // Sudut detail sambungan / Gapura
            $table->string('foto_proses_path')->nullable(); // Proses pembuatan / Bivak
            $table->string('foto_extra_path')->nullable();

            $table->foreignId('creator_id')->nullable()->constrained('anggota_regu')->nullOnDelete();
            $table->enum('status', ['draft', 'submitted'])->default('submitted');

            $table->timestamps();

            // Unique submission per regu & mata lomba per season
            $table->unique(['season_id', 'regu_profile_id', 'mata_lomba_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_lombas');
    }
};
