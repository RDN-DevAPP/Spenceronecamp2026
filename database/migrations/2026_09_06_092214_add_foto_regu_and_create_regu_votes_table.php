<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->string('foto_regu_path')->nullable()->after('nomor_kavling');
            $table->text('foto_regu_caption')->nullable()->after('foto_regu_path');
        });

        Schema::create('regu_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('voter_identifier', 128)->index(); // Cookie / device hash
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Unique index to prevent duplicate votes
            $table->unique('voter_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regu_votes');

        Schema::table('regu_profiles', function (Blueprint $table) {
            $table->dropColumn(['foto_regu_path', 'foto_regu_caption']);
        });
    }
};
