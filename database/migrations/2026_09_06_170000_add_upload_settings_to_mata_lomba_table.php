<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mata_lomba', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_lomba', 'wajib_upload_foto')) {
                $table->boolean('wajib_upload_foto')->default(false)->after('is_scored');
            }
            if (!Schema::hasColumn('mata_lomba', 'instruksi_upload_foto')) {
                $table->text('instruksi_upload_foto')->nullable()->after('wajib_upload_foto');
            }
            if (!Schema::hasColumn('mata_lomba', 'max_foto')) {
                $table->integer('max_foto')->default(1)->after('instruksi_upload_foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mata_lomba', function (Blueprint $table) {
            $table->dropColumn(['wajib_upload_foto', 'instruksi_upload_foto', 'max_foto']);
        });
    }
};
