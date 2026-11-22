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
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->text('tenda_description')->nullable()->after('foto_tenda_path');
            $table->text('masakan_description')->nullable()->after('foto_masakan_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->dropColumn(['tenda_description', 'masakan_description']);
        });
    }
};
