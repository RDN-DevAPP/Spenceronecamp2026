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
        // 1. Update scores table with status lifecycle & unlock workflow
        Schema::table('scores', function (Blueprint $table) {
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft')->after('nilai');
            $table->boolean('unlock_requested')->default(false)->after('status');
            $table->text('unlock_reason')->nullable()->after('unlock_requested');
            $table->timestamp('submitted_at')->nullable()->after('unlock_reason');
            $table->timestamp('verified_at')->nullable()->after('submitted_at');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
        });

        // 2. Create score_audit_logs table
        Schema::create('score_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('score_id')->nullable()->constrained('scores')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('regu_profile_id')->constrained('regu_profiles')->cascadeOnDelete();
            $table->foreignId('mata_lomba_id')->constrained('mata_lomba')->cascadeOnDelete();
            $table->string('action'); // draft_saved, submitted, unlock_requested, unlock_approved, unlock_rejected, verified, deleted
            $table->decimal('old_score', 5, 2)->nullable();
            $table->decimal('new_score', 5, 2)->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('reason')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_audit_logs');

        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'status',
                'unlock_requested',
                'unlock_reason',
                'submitted_at',
                'verified_at',
                'verified_by',
            ]);
        });
    }
};
