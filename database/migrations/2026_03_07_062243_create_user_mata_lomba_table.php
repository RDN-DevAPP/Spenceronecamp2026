<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_mata_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mata_lomba_id')->constrained('mata_lomba')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'mata_lomba_id']);
        });

        // Migrate existing mata_lomba_id data to pivot table
        $juris = DB::table('users')->whereNotNull('mata_lomba_id')->where('role', 'juri')->get();
        foreach ($juris as $juri) {
            DB::table('user_mata_lomba')->insert([
                'user_id' => $juri->id,
                'mata_lomba_id' => $juri->mata_lomba_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_mata_lomba');
    }
};
