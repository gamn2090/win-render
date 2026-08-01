<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Vendors need to block off events that aren't tied to a WIN-booked
     * couple (a day off, a non-WIN client's wedding, etc.) — client_id was
     * required, which made that impossible. Make it optional and add a
     * free-text title for that case.
     *
     * No doctrine/dbal dependency here, so alter the column with raw SQL
     * instead of ->change() (same approach as
     * 2026_07_06_000000_change_vendors_score_to_decimal.php).
     */
    public function up(): void
    {
        Schema::table('vendor_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE vendor_calendar_events ALTER COLUMN client_id DROP NOT NULL');
        }

        Schema::table('vendor_calendar_events', function (Blueprint $table) {
            $table->string('title')->nullable()->after('client_id');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_calendar_events', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('title');
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('DELETE FROM vendor_calendar_events WHERE client_id IS NULL');
            DB::statement('ALTER TABLE vendor_calendar_events ALTER COLUMN client_id SET NOT NULL');
        }

        Schema::table('vendor_calendar_events', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
