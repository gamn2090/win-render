<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `approved` defaulted to false, which meant "pending" and "declined"
     * were indistinguishable. Vendor accept/reject also tried to store -1
     * for a decline, which Postgres rejects outright for a boolean column
     * ("invalid input syntax for type boolean: -1"). Making the column
     * nullable gives three real states: null=pending, true=accepted,
     * false=declined. No doctrine/dbal dependency, so raw SQL like the
     * vendors.score migration.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE meetings ALTER COLUMN approved DROP NOT NULL');
            DB::statement('ALTER TABLE meetings ALTER COLUMN approved DROP DEFAULT');
            DB::statement("UPDATE meetings SET approved = NULL WHERE approved = false");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('UPDATE meetings SET approved = false WHERE approved IS NULL');
            DB::statement('ALTER TABLE meetings ALTER COLUMN approved SET DEFAULT false');
            DB::statement('ALTER TABLE meetings ALTER COLUMN approved SET NOT NULL');
        }
    }
};
