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
            // Phone number (normalized: digits only, starts with 62) as unique identity lock
            $table->string('phone_number', 20)->nullable()->unique()->after('user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_votes', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
            $table->dropColumn('phone_number');
        });
    }
};
