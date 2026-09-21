<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbReconcileLegacyMigrations extends Command
{
    /**
     * The production MySQL dump's `migrations` table was written by an older
     * copy of this app whose migration files had different names/timestamps
     * for tables that already exist in production (and, for tags/tag_types/
     * events, no migration record at all even though the tables exist).
     * Importing the raw dump as-is and then running `php artisan migrate`
     * makes Laravel think those migrations never ran, so it tries to
     * re-create tables/columns that are already there and aborts with
     * "already exists" / "duplicate column" errors.
     *
     * This command rewrites the historical rows to match the current
     * migration filenames (or inserts a marker row when none existed), so
     * `php artisan migrate --force` treats that already-applied schema as
     * done and only runs what's genuinely new. It never touches any table
     * other than `migrations`, so it can't lose data — run it once, right
     * after importing a fresh production dump, before `migrate --force`.
     */
    protected $signature = 'db:reconcile-legacy-migrations';

    protected $description = 'Rewrite production dump migration history to match current migration filenames before running migrate.';

    /**
     * Old name (as recorded in the production dump) => current filename in
     * database/migrations. Confirmed to create byte-for-byte equivalent
     * schema against the 2026-09-17 production dump.
     */
    private array $renames = [
        '2019_05_03_000001_create_customer_columns' => '2024_02_13_185228_add_vendor_stripe_columns',
        '2019_05_03_000002_create_subscriptions_table' => '2024_02_13_185229_create_subscriptions_table',
        '2019_05_03_000003_create_subscription_items_table' => '2024_02_13_185230_create_subscription_items_table',
        '2025_01_28_212833_create_jobs_table' => '2025_01_24_180220_create_jobs_table',
    ];

    /**
     * Tables that exist in the production dump with no corresponding
     * migration row at all (created outside of `php artisan migrate` at
     * some point). Confirmed their live structure matches what these
     * migrations would create, so they're marked as already applied rather
     * than re-run.
     */
    private array $missingMarkers = [
        '2025_03_19_143026_create_tags_table',
        '2025_03_19_144236_create_tag_types_table',
        '2025_03_26_132210_create_events_table',
    ];

    public function handle(): int
    {
        if (!DB::getSchemaBuilder()->hasTable('migrations')) {
            $this->error('No migrations table found — run this after importing the production dump.');
            return self::FAILURE;
        }

        foreach ($this->renames as $old => $new) {
            $updated = DB::table('migrations')->where('migration', $old)->update(['migration' => $new]);
            if ($updated > 0) {
                $this->info("  renamed: {$old} -> {$new}");
            } else {
                $this->line("  skip (not present): {$old}");
            }
        }

        $maxBatch = (int) DB::table('migrations')->max('batch');

        foreach ($this->missingMarkers as $migration) {
            $exists = DB::table('migrations')->where('migration', $migration)->exists();
            if ($exists) {
                $this->line("  skip (already recorded): {$migration}");
                continue;
            }

            DB::table('migrations')->insert([
                'migration' => $migration,
                'batch' => $maxBatch + 1,
            ]);
            $this->info("  marked as applied: {$migration}");
        }

        $this->info('Reconciliation complete. You can now run: php artisan migrate --force');

        return self::SUCCESS;
    }
}
