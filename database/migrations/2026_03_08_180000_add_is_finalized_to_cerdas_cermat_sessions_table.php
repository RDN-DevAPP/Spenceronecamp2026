<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            $table->boolean('is_finalized_round_2')->default(false)->after('is_graded_round_2');
            $table->boolean('is_finalized_round_3')->default(false)->after('is_finalized_round_2');
        });
    }

    public function down(): void
    {
        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            $table->dropColumn(['is_finalized_round_2', 'is_finalized_round_3']);
        });
    }
};
