<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scoring_criteria', function (Blueprint $table) {
            if (!Schema::hasColumn('scoring_criteria', 'kelompok')) {
                $table->string('kelompok')->nullable()->after('nama_kriteria');
            }
            if (!Schema::hasColumn('scoring_criteria', 'rubrik')) {
                $table->json('rubrik')->nullable()->after('kelompok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('scoring_criteria', function (Blueprint $table) {
            if (Schema::hasColumn('scoring_criteria', 'rubrik')) {
                $table->dropColumn('rubrik');
            }
            if (Schema::hasColumn('scoring_criteria', 'kelompok')) {
                $table->dropColumn('kelompok');
            }
        });
    }
};
