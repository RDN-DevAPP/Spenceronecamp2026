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
            $table->string('foto_tenda_path')->nullable()->after('poster_digital_path');
            $table->string('foto_masakan_path')->nullable()->after('foto_tenda_path');
            $table->string('foto_karya_path')->nullable()->after('foto_masakan_path');
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
