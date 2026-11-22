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
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->string('kategori')->default('lomba')->after('slug'); // 'lomba', 'giat_alam', 'giat_seni'
            $table->boolean('is_scored')->default(true)->after('kategori'); // true for scoring competition, false for supporting activities
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'is_scored']);
        });
    }
};
