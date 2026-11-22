<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_coin_royale_rounds', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_coin_royale_rounds', 'question_image_path')) {
                $table->string('question_image_path')->nullable()->after('question_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_coin_royale_rounds', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_coin_royale_rounds', 'question_image_path')) {
                $table->dropColumn('question_image_path');
            }
        });
    }
};
