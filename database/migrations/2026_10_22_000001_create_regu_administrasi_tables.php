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
        // 1. Tambah field NTA dan biodata pelengkap pada anggota_regu
        Schema::table('anggota_regu', function (Blueprint $table) {
            if (!Schema::hasColumn('anggota_regu', 'nta')) {
                $table->string('nta', 50)->nullable()->after('nisn')->comment('Nomor Tanda Anggota Pramuka');
            }
            if (!Schema::hasColumn('anggota_regu', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable()->after('kelas');
            }
            if (!Schema::hasColumn('anggota_regu', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('anggota_regu', 'agama')) {
                $table->string('agama', 50)->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('anggota_regu', 'alamat')) {
                $table->text('alamat')->nullable()->after('agama');
            }
            if (!Schema::hasColumn('anggota_regu', 'tkk')) {
                $table->text('tkk')->nullable()->after('tingkatan_tku')->comment('Daftar TKK yang dimiliki');
            }
        });

        // 2. Buku Keuangan Kas Regu
        Schema::create('regu_finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('uraian');
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('nominal', 15, 2);
            $table->string('kategori')->nullable(); // Iuran, Perlengkapan, Konsumsi, dll.
            $table->string('bukti_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Buku Catatan Kegiatan (Jurnal / Logbook Regu)
        Schema::create('regu_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('waktu_mulai', 20)->nullable();
            $table->string('waktu_selesai', 20)->nullable();
            $table->string('nama_kegiatan');
            $table->string('tempat')->nullable();
            $table->string('pemimpin_kegiatan')->nullable();
            $table->text('ringkasan_materi')->nullable();
            $table->text('hasil_kegiatan')->nullable();
            $table->string('foto_kegiatan_path')->nullable();
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamps();
        });

        // 4. Buku Notulen Rapat Regu
        Schema::create('regu_meeting_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('waktu', 30)->nullable();
            $table->string('tempat')->nullable();
            $table->string('agenda');
            $table->string('pemimpin_rapat')->nullable();
            $table->string('notulis')->nullable();
            $table->integer('jumlah_hadir')->default(0);
            $table->text('daftar_hadir_teks')->nullable();
            $table->text('jalannya_rapat')->nullable();
            $table->text('keputusan');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });

        // 5. Buku Presensi Regu (Master & Detail)
        Schema::create('regu_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_kegiatan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('regu_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_attendance_id')->constrained('regu_attendances')->onDelete('cascade');
            $table->foreignId('anggota_regu_id')->constrained('anggota_regu')->onDelete('cascade');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. Buku Inventaris Perlengkapan Regu
        Schema::create('regu_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->onDelete('cascade');
            $table->string('kode_barang', 50)->nullable();
            $table->string('nama_barang');
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('Buah');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('asal_barang')->nullable();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('lokasi_penyimpanan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regu_inventories');
        Schema::dropIfExists('regu_attendance_details');
        Schema::dropIfExists('regu_attendances');
        Schema::dropIfExists('regu_meeting_notes');
        Schema::dropIfExists('regu_logbooks');
        Schema::dropIfExists('regu_finances');

        Schema::table('anggota_regu', function (Blueprint $table) {
            $columns = ['nta', 'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'tkk'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('anggota_regu', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
