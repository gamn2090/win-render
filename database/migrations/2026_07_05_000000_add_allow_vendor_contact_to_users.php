<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'allow_vendor_contact')) {
                $table->boolean('allow_vendor_contact')->default(true)->after('event');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'allow_vendor_contact')) {
                $table->dropColumn('allow_vendor_contact');
            }
        });
    }
};
