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
        // 1. Buat tabel transaksi vote berbayar
        Schema::create('paid_vote_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 64)->unique();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('voter_name', 100);
            $table->string('voter_identifier', 128)->index();
            $table->string('ip_address', 45);
            $table->unsignedInteger('votes_count')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->string('proof_path', 255)->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->index();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // 2. Modifikasi tabel regu_votes untuk mendukung multi-vote dan tautan transaksi
        Schema::table('regu_votes', function (Blueprint $table) {
            // Drop unique constraint agar 1 perangkat bisa vote harian berulang setelah reset atau membeli multi-vote
            $table->dropUnique('regu_votes_voter_identifier_unique');

            // Tambahkan jenis vote (free / paid) dan foreign key ke transaksi
            $table->string('vote_type', 20)->default('free')->after('voter_name');
            $table->foreignId('paid_vote_transaction_id')
                ->nullable()
                ->after('vote_type')
                ->constrained('paid_vote_transactions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regu_votes', function (Blueprint $table) {
            $table->dropForeign(['paid_vote_transaction_id']);
            $table->dropColumn(['vote_type', 'paid_vote_transaction_id']);
            $table->unique('voter_identifier', 'regu_votes_voter_identifier_unique');
        });

        Schema::dropIfExists('paid_vote_transactions');
    }
};
