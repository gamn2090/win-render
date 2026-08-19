<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Trending recalculates weekly (storefront_views_week) and Fast Responder
     * recalculates daily (fast_responses_today) — both reset on a schedule by
     * vendors:recalculate-rankings, matching the existing storefront_views_month
     * pattern used for the Trending badge's earlier monthly iteration.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'storefront_views_week')) {
                $table->integer('storefront_views_week')->default(0)->after('storefront_views_month');
            }
            if (! Schema::hasColumn('vendors', 'fast_responses_today')) {
                $table->integer('fast_responses_today')->default(0)->after('fast_responses');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'storefront_views_week')) {
                $table->dropColumn('storefront_views_week');
            }
            if (Schema::hasColumn('vendors', 'fast_responses_today')) {
                $table->dropColumn('fast_responses_today');
            }
        });
    }
};
