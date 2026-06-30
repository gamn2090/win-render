<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Controllers order vendor types by `priority`; the original create table migration did not include this column.
     */
    public function up(): void
    {
        if (! Schema::hasTable('vendor_types')) {
            return;
        }

        if (! Schema::hasColumn('vendor_types', 'priority')) {
            Schema::table('vendor_types', function (Blueprint $table) {
                $table->unsignedInteger('priority')->default(0)->after('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vendor_types') && Schema::hasColumn('vendor_types', 'priority')) {
            Schema::table('vendor_types', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
