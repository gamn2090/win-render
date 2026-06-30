<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Musonza\Chat\ConfigurationManager;

return new class extends Migration
{
    public function up(): void
    {
        $table = ConfigurationManager::MESSAGES_TABLE;

        if (!Schema::hasColumn($table, 'is_encrypted')) {
            Schema::table($table, function (Blueprint $table) {
                $table->boolean('is_encrypted')->default(false)->after('data');
            });
        }
    }

    public function down(): void
    {
        $table = ConfigurationManager::MESSAGES_TABLE;

        if (Schema::hasColumn($table, 'is_encrypted')) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('is_encrypted');
            });
        }
    }
};
