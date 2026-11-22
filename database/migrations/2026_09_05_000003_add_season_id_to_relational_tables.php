<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of relational tables that require season_id isolation.
     */
    protected array $tables = [
        'regu_profiles',
        'mata_lomba',
        'scores',
        'jadwals',
        'cerdas_cermat_sessions',
        'sponsorships',
        'financial_reports',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'season_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('season_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('seasons')
                        ->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'season_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropForeign([$tableName . '_season_id_foreign']);
                    $table->dropColumn('season_id');
                });
            }
        }
    }
};
