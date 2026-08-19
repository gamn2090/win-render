<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Re-adds a plain venue NAME field (distinct from wedding_location, which
     * is city/state) — previously hacked into a "Wedding venue: X" prefix on
     * the bio column; this gives it a real column instead. Not required.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'wedding_venue_name')) {
                $table->string('wedding_venue_name')->nullable()->after('wedding_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'wedding_venue_name')) {
                $table->dropColumn('wedding_venue_name');
            }
        });
    }
};
