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
        Schema::table('regu_votes', function (Blueprint $table) {
            // Drop phone_number unique constraint and column
            $table->dropUnique(['phone_number']);
            $table->dropColumn('phone_number');

            // Add voter display name (no unique constraint — locked by cookie + 24h logic)
            $table->string('voter_name', 100)->nullable()->after('user_agent');

            // Explicit voted_at timestamp (separate from created_at for clarity)
            $table->timestamp('voted_at')->nullable()->after('voter_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_votes', function (Blueprint $table) {
            $table->dropColumn(['voter_name', 'voted_at']);
            $table->string('phone_number', 20)->nullable()->unique()->after('user_agent');
        });
    }
};
