<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->string('kode', 10)->unique()->nullable()->after('nama');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('mata_lomba_id')->nullable()->after('role')
                ->constrained('mata_lomba')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mata_lomba_id');
        });

        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
