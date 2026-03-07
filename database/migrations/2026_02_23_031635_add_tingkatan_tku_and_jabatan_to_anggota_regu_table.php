<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggota_regu', function (Blueprint $table) {
            if (Schema::hasColumn('anggota_regu', 'tingkatan_tku')) {
                $table->dropColumn('tingkatan_tku');
            }
            if (Schema::hasColumn('anggota_regu', 'jabatan')) {
                $table->dropColumn('jabatan');
            }
        });

        Schema::table('anggota_regu', function (Blueprint $table) {
            $table->enum('tingkatan_tku', ['ramu', 'rakit', 'terap'])->nullable()->after('nomor_punggung');
            $table->enum('jabatan', ['pinru', 'wapinru', 'anggota'])->nullable()->after('tingkatan_tku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_regu', function (Blueprint $table) {
            $table->dropColumn(['tingkatan_tku', 'jabatan']);
        });
    }
};
