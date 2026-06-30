<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns present in the client MySQL dump / database/exports snapshot but missing
     * from early Laravel migrations. Required for SnapshotSeeder on PostgreSQL (Render).
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'uuid')) {
                    $table->uuid('uuid')->nullable()->after('id');
                }
                if (! Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (! Schema::hasColumn('users', 'booking_date')) {
                    $table->string('booking_date')->nullable()->after('wedding_date');
                }
                if (! Schema::hasColumn('users', 'ref_source')) {
                    $table->unsignedInteger('ref_source')->nullable()->after('in_network');
                }
                if (! Schema::hasColumn('users', 'event')) {
                    $table->string('event')->nullable()->after('ref_source');
                }
                if (! Schema::hasColumn('users', 'questions')) {
                    $table->text('questions')->nullable()->after('bio');
                }
            });
        }

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                if (! Schema::hasColumn('vendors', 'uuid')) {
                    $table->uuid('uuid')->nullable()->after('id');
                }
                if (! Schema::hasColumn('vendors', 'score')) {
                    $table->integer('score')->default(0)->after('type');
                }
                if (! Schema::hasColumn('vendors', 'avg_price')) {
                    $table->string('avg_price')->nullable()->after('discount');
                }
                if (! Schema::hasColumn('vendors', 'badges')) {
                    $table->text('badges')->nullable()->after('ref_by');
                }
                if (! Schema::hasColumn('vendors', 'visible')) {
                    $table->boolean('visible')->default(true)->after('badges');
                }
                if (! Schema::hasColumn('vendors', 'contact_credits')) {
                    $table->integer('contact_credits')->default(0)->after('slow_responses');
                }
            });
        }

        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                if (! Schema::hasColumn('profiles', 'notes')) {
                    $table->text('notes')->nullable()->after('bio');
                }
                if (! Schema::hasColumn('profiles', 'google_reviews_count')) {
                    $table->unsignedInteger('google_reviews_count')->nullable()->after('google_review_score');
                }
                if (! Schema::hasColumn('profiles', 'google_place_link')) {
                    $table->string('google_place_link')->nullable()->after('google_reviews_count');
                }
            });
        }

        if (Schema::hasTable('pairings')) {
            Schema::table('pairings', function (Blueprint $table) {
                if (! Schema::hasColumn('pairings', 'status')) {
                    $table->unsignedTinyInteger('status')->default(0)->after('client_id');
                }
                if (! Schema::hasColumn('pairings', 'vendor_type')) {
                    $table->unsignedInteger('vendor_type')->nullable()->after('status');
                }
                if (! Schema::hasColumn('pairings', 'active')) {
                    $table->boolean('active')->default(true)->after('approved');
                }
            });
        }

        if (Schema::hasTable('meetings')) {
            Schema::table('meetings', function (Blueprint $table) {
                if (! Schema::hasColumn('meetings', 'uuid')) {
                    $table->uuid('uuid')->nullable()->after('id');
                }
            });
        }

        if (Schema::hasTable('referrals')) {
            Schema::table('referrals', function (Blueprint $table) {
                if (! Schema::hasColumn('referrals', 'uuid')) {
                    $table->uuid('uuid')->nullable()->after('id');
                }
            });
        }

        if (Schema::hasTable('endorsements')) {
            Schema::table('endorsements', function (Blueprint $table) {
                if (! Schema::hasColumn('endorsements', 'vendor_id')) {
                    $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
                }
                if (! Schema::hasColumn('endorsements', 'type')) {
                    $table->unsignedInteger('type')->nullable()->after('vendor_id');
                }
                if (! Schema::hasColumn('endorsements', 'endorser')) {
                    $table->unsignedBigInteger('endorser')->nullable()->after('type');
                }
            });
        }

        if (Schema::hasTable('vendor_rankings')) {
            Schema::table('vendor_rankings', function (Blueprint $table) {
                if (! Schema::hasColumn('vendor_rankings', 'vendor_id')) {
                    $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
                }
                if (! Schema::hasColumn('vendor_rankings', 'client_community')) {
                    $table->integer('client_community')->default(0)->after('vendor_id');
                }
                if (! Schema::hasColumn('vendor_rankings', 'vendor_community')) {
                    $table->integer('vendor_community')->default(0)->after('client_community');
                }
                if (! Schema::hasColumn('vendor_rankings', 'reviews')) {
                    $table->integer('reviews')->default(0)->after('vendor_community');
                }
                if (! Schema::hasColumn('vendor_rankings', 'endorsements')) {
                    $table->integer('endorsements')->default(0)->after('reviews');
                }
                if (! Schema::hasColumn('vendor_rankings', 'badges')) {
                    $table->integer('badges')->default(0)->after('endorsements');
                }
            });
        }

        $this->widenSnapshotTextColumns();
    }

    /**
     * Client DB uses TEXT for long couple/vendor bios; base migrations used VARCHAR(255).
     */
    private function widenSnapshotTextColumns(): void
    {
        $driver = DB::getDriverName();

        $textColumns = [
            'users' => ['bio'],
            'profiles' => ['bio', 'portfolio_images', 'notes'],
            'reviews' => ['body', 'author_photo'],
        ];

        foreach ($textColumns as $table => $columns) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    continue;
                }

                if ($driver === 'pgsql') {
                    DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} TYPE TEXT");
                } elseif ($driver === 'mysql') {
                    DB::statement("ALTER TABLE {$table} MODIFY {$column} TEXT NULL");
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vendor_rankings')) {
            Schema::table('vendor_rankings', function (Blueprint $table) {
                $cols = ['vendor_id', 'client_community', 'vendor_community', 'reviews', 'endorsements', 'badges'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('vendor_rankings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('endorsements')) {
            Schema::table('endorsements', function (Blueprint $table) {
                foreach (['vendor_id', 'type', 'endorser'] as $col) {
                    if (Schema::hasColumn('endorsements', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('referrals')) {
            Schema::table('referrals', function (Blueprint $table) {
                if (Schema::hasColumn('referrals', 'uuid')) {
                    $table->dropColumn('uuid');
                }
            });
        }

        if (Schema::hasTable('meetings')) {
            Schema::table('meetings', function (Blueprint $table) {
                if (Schema::hasColumn('meetings', 'uuid')) {
                    $table->dropColumn('uuid');
                }
            });
        }

        if (Schema::hasTable('pairings')) {
            Schema::table('pairings', function (Blueprint $table) {
                foreach (['status', 'vendor_type', 'active'] as $col) {
                    if (Schema::hasColumn('pairings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('profiles')) {
            Schema::table('profiles', function (Blueprint $table) {
                foreach (['notes', 'google_reviews_count', 'google_place_link'] as $col) {
                    if (Schema::hasColumn('profiles', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('vendors')) {
            Schema::table('vendors', function (Blueprint $table) {
                foreach (['uuid', 'score', 'avg_price', 'badges', 'visible', 'contact_credits'] as $col) {
                    if (Schema::hasColumn('vendors', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                foreach (['uuid', 'phone', 'booking_date', 'ref_source', 'event', 'questions'] as $col) {
                    if (Schema::hasColumn('users', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
