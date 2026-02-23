<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sponsorships', function (Blueprint $table) {
            $table->string('pic_name')->after('name');
            $table->string('email')->after('pic_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('receipt')->nullable()->after('logo');
            $table->boolean('is_approved')->default(false)->after('receipt');
            $table->string('tier')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsorships', function (Blueprint $table) {
            $table->dropColumn(['pic_name', 'email', 'phone', 'receipt', 'is_approved']);
            $table->string('tier')->nullable(false)->change();
        });
    }
};
