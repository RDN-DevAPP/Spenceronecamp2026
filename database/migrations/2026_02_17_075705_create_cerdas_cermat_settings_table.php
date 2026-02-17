<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cerdas_cermat_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        // Seed default values
        DB::table('cerdas_cermat_settings')->insert([
            ['key' => 'round_1_duration', 'value' => '60', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'round_2_duration', 'value' => '35', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'round_3_duration', 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cerdas_cermat_settings');
    }
};
