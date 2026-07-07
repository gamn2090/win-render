<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vendor::calculateScore()/updateAllRankingScores() compute a weighted
     * average (e.g. 2.5), but the column was created as integer, so saving
     * fails with "invalid input syntax for type integer". No doctrine/dbal
     * dependency here, so alter the type with raw SQL instead of ->change().
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vendors ALTER COLUMN score TYPE numeric(8,2) USING score::numeric(8,2)');
            DB::statement('ALTER TABLE vendors ALTER COLUMN score SET DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vendors ALTER COLUMN score TYPE integer USING round(score)::integer');
            DB::statement('ALTER TABLE vendors ALTER COLUMN score SET DEFAULT 0');
        }
    }
};
