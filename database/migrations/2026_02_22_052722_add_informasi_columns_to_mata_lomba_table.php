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
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->longText('petunjuk_teknis')->nullable();
            $table->longText('ketentuan_pelaksanaan')->nullable();
            $table->longText('kriteria_penilaian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->dropColumn(['petunjuk_teknis', 'ketentuan_pelaksanaan', 'kriteria_penilaian']);
        });
    }
};
