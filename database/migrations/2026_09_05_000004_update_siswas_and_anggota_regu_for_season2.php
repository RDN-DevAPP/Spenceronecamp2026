<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'nisn')) {
                $table->string('nisn', 20)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('siswas', 'tingkatan_tku')) {
                $table->enum('tingkatan_tku', ['ramu', 'rakit', 'terap'])->default('ramu')->after('kelas');
            }
            if (!Schema::hasColumn('siswas', 'season_id')) {
                $table->foreignId('season_id')->nullable()->after('id')->constrained('seasons')->nullOnDelete();
            }
        });

        Schema::table('anggota_regu', function (Blueprint $table) {
            if (!Schema::hasColumn('anggota_regu', 'nisn')) {
                $table->string('nisn', 20)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('anggota_regu', 'foto_path')) {
                $table->string('foto_path')->nullable()->after('jabatan');
            }
        });

        Schema::table('regu_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('regu_profiles', 'nomor_kavling')) {
                $table->string('nomor_kavling', 20)->nullable()->after('nomor_regu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'season_id')) {
                $table->dropForeign(['season_id']);
                $table->dropColumn('season_id');
            }
            if (Schema::hasColumn('siswas', 'nisn')) {
                $table->dropColumn('nisn');
            }
            if (Schema::hasColumn('siswas', 'tingkatan_tku')) {
                $table->dropColumn('tingkatan_tku');
            }
        });

        Schema::table('anggota_regu', function (Blueprint $table) {
            if (Schema::hasColumn('anggota_regu', 'nisn')) {
                $table->dropColumn('nisn');
            }
            if (Schema::hasColumn('anggota_regu', 'foto_path')) {
                $table->dropColumn('foto_path');
            }
        });

        Schema::table('regu_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('regu_profiles', 'nomor_kavling')) {
                $table->dropColumn('nomor_kavling');
            }
        });
    }
};
