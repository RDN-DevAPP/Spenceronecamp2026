<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('anggota_regu', function (Blueprint $table) {
            if (!Schema::hasColumn('anggota_regu', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('foto_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('anggota_regu', function (Blueprint $table) {
            if (Schema::hasColumn('anggota_regu', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
        });
    }
};
