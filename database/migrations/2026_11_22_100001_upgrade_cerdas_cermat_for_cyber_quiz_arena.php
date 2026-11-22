<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('cerdas_cermat_sessions', 'name_1')) {
                $table->string('name_1')->nullable()->change();
                $table->string('name_2')->nullable()->change();
                $table->string('name_3')->nullable()->change();
            }

            if (!Schema::hasColumn('cerdas_cermat_sessions', 'coin_initial')) {
                $table->integer('coin_initial')->default(0)->after('score_round_3');
            }
            if (!Schema::hasColumn('cerdas_cermat_sessions', 'coin_balance')) {
                $table->integer('coin_balance')->default(0)->after('coin_initial');
            }
            if (!Schema::hasColumn('cerdas_cermat_sessions', 'round_1_last_activity_at')) {
                $table->timestamp('round_1_last_activity_at')->nullable()->after('end_time_round_1');
            }
            if (!Schema::hasColumn('cerdas_cermat_sessions', 'round_2_last_activity_at')) {
                $table->timestamp('round_2_last_activity_at')->nullable()->after('end_time_round_2');
            }
        });

        // Babak 1: QR-Hunt Trivia
        Schema::create('quiz_qr_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_lomba_id')->nullable()->constrained('mata_lomba')->nullOnDelete();
            $table->string('code_label', 50)->nullable(); // e.g., QR-001
            $table->string('qr_token', 64)->unique();
            $table->text('question');
            $table->string('image_path')->nullable();
            $table->json('accepted_answers'); // Array of case-insensitive valid answers
            $table->integer('points')->default(100);
            $table->string('status', 20)->default('available')->index(); // available | claimed
            $table->foreignId('claimed_by_regu_id')->nullable()->constrained('regu_profiles')->nullOnDelete();
            $table->foreignId('claimed_by_session_id')->nullable()->constrained('cerdas_cermat_sessions')->nullOnDelete();
            $table->string('claimed_answer')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_qr_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_question_id')->constrained('quiz_qr_questions')->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('cerdas_cermat_sessions')->cascadeOnDelete();
            $table->foreignId('regu_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->string('submitted_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('points_awarded')->default(0);
            $table->string('status', 30)->default('opened'); // opened | correct | wrong | rejected_claimed
            $table->timestamps();
        });

        // Babak 2: Cipher Word-Search
        Schema::create('quiz_cipher_grids', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cipher_type', 20)->default('kotak_1'); // kotak_1 | kotak_2 | angka
            $table->unsignedTinyInteger('grid_size')->default(15); // 12..20
            $table->json('grid_matrix'); // 2D array [row][col] of A-Z
            $table->json('word_placements')->nullable(); // Coordinates for answer key
            $table->integer('default_points')->default(50);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quiz_cipher_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cipher_grid_id')->constrained('quiz_cipher_grids')->cascadeOnDelete();
            $table->string('word', 64);
            $table->string('hint')->nullable();
            $table->integer('points')->default(50);
            $table->boolean('is_claimed')->default(false)->index();
            $table->foreignId('claimed_by_regu_id')->nullable()->constrained('regu_profiles')->nullOnDelete();
            $table->foreignId('claimed_by_session_id')->nullable()->constrained('cerdas_cermat_sessions')->nullOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['cipher_grid_id', 'word']);
        });

        // Babak 3: Final "Coin Royale" (9 Ronde Taruhan Taktis untuk Top 5 Regu)
        Schema::create('quiz_coin_royale_rounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('round_number')->unique(); // 1..9
            $table->boolean('is_special')->default(false); // true for rounds 3, 6, 9
            $table->text('question_text')->nullable();
            $table->text('correct_answer')->nullable();
            $table->string('phase', 30)->default('PENDING'); // PENDING | BETTING_OPEN | BETTING_LOCKED | QUESTION_OPEN | GRADED
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('quiz_coin_royale_bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_royale_round_id')->constrained('quiz_coin_royale_rounds')->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('cerdas_cermat_sessions')->cascadeOnDelete();
            $table->foreignId('regu_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->string('action', 10)->default('BET'); // BET | PASS
            $table->integer('bet_amount')->default(0);
            $table->integer('balance_before')->default(0);
            $table->string('result', 20)->default('PENDING'); // PENDING | CORRECT | WRONG | PASS
            $table->integer('balance_after')->nullable();
            $table->integer('coin_delta')->default(0);
            $table->timestamps();

            $table->unique(['coin_royale_round_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_coin_royale_bets');
        Schema::dropIfExists('quiz_coin_royale_rounds');
        Schema::dropIfExists('quiz_cipher_words');
        Schema::dropIfExists('quiz_cipher_grids');
        Schema::dropIfExists('quiz_qr_attempts');
        Schema::dropIfExists('quiz_qr_questions');

        Schema::table('cerdas_cermat_sessions', function (Blueprint $table) {
            $cols = array_filter([
                'coin_initial',
                'coin_balance',
                'round_1_last_activity_at',
                'round_2_last_activity_at',
            ], fn($c) => Schema::hasColumn('cerdas_cermat_sessions', $c));

            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
