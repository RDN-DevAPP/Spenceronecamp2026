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
        Schema::table('cerdas_cermat_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('cerdas_cermat_questions', 'image_path')) {
                $table->string('image_path')->nullable()->after('question');
            }
        });

        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('cerdas_cermat_sessions', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('status');
            }
            if (!Schema::hasColumn('cerdas_cermat_sessions', 'warning_count')) {
                $table->integer('warning_count')->default(0)->after('is_locked');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cerdas_cermat_questions', function (Blueprint $table) {
            if (Schema::hasColumn('cerdas_cermat_questions', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });

        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('cerdas_cermat_sessions', 'is_locked')) {
                $table->dropColumn(['is_locked', 'warning_count']);
            }
        });
    }
};
