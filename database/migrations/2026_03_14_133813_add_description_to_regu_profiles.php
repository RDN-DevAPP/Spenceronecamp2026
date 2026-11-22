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
            $table->text('poster_description')->nullable()->after('poster_creator_id');
            $table->text('upcycle_description')->nullable()->after('upcycle_creator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->dropColumn(['poster_description', 'upcycle_description']);
        });
    }
};
