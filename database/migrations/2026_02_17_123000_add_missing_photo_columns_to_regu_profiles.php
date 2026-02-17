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
        Schema::table('regu_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('regu_profiles', 'foto_tenda_path')) {
                $table->string('foto_tenda_path')->nullable()->after('poster_digital_path');
            }
            if (!Schema::hasColumn('regu_profiles', 'foto_masakan_path')) {
                $table->string('foto_masakan_path')->nullable()->after('poster_digital_path'); // adjust after if needed
            }
            if (!Schema::hasColumn('regu_profiles', 'foto_karya_path')) {
                $table->string('foto_karya_path')->nullable()->after('poster_digital_path'); // adjust after if needed
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->dropColumn(['foto_tenda_path', 'foto_masakan_path', 'foto_karya_path']);
        });
    }
};
