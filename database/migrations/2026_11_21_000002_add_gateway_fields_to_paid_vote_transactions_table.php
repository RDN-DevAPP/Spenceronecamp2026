<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paid_vote_transactions', function (Blueprint $table) {
            $table->text('qris_payload')->nullable()->after('payment_method');
            $table->decimal('gateway_fee', 12, 2)->default(0)->after('qris_payload');
            $table->decimal('gateway_total_payment', 12, 2)->nullable()->after('gateway_fee');
            $table->timestamp('expired_at')->nullable()->after('gateway_total_payment');
            $table->json('gateway_response')->nullable()->after('expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('paid_vote_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'qris_payload',
                'gateway_fee',
                'gateway_total_payment',
                'expired_at',
                'gateway_response',
            ]);
        });
    }
};
