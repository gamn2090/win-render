<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The "Trending" badge is supposed to reflect recent momentum (top 15% of
     * storefront views this month), but storefront_views is a lifetime
     * counter that never resets — this column gives it a real monthly value,
     * zeroed out by vendors:recalculate-rankings on the 1st of each month.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (! Schema::hasColumn('vendors', 'storefront_views_month')) {
                $table->integer('storefront_views_month')->default(0)->after('storefront_views');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'storefront_views_month')) {
                $table->dropColumn('storefront_views_month');
            }
        });
    }
};
