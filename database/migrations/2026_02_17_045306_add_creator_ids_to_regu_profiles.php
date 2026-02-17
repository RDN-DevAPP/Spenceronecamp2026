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
            $table->foreignId('poster_creator_id')->nullable()->constrained('anggota_regu')->nullOnDelete();
            $table->foreignId('upcycle_creator_id')->nullable()->constrained('anggota_regu')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->dropForeign(['poster_creator_id']);
            $table->dropForeign(['upcycle_creator_id']);
            $table->dropColumn(['poster_creator_id', 'upcycle_creator_id']);
        });
    }
};
