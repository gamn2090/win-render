<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `meetings.date` was created as a `date` column, so Postgres silently
     * truncated the time portion on every insert — the hour the couple
     * picked when scheduling a consultation was never actually persisted,
     * which is why appointment cards always showed midnight. No
     * doctrine/dbal dependency, so raw SQL like the vendors.score migration.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE meetings ALTER COLUMN date TYPE timestamp USING date::timestamp');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE meetings ALTER COLUMN date TYPE date USING date::date');
        }
    }
};
