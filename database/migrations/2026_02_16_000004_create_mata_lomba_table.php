<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 6 mata lomba: Tapak Kemah, Masak Konvensional, Upcycle Art, LKBB Tongkat, Cerdas Cermat, Desain Poster Digital.
     */
    public function up(): void
    {
        Schema::create('mata_lomba', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('urutan')->default(1);
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('nilai_maksimal')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_lomba');
    }
};
