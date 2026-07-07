<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeployFreshSeed extends Command
{
    /**
     * Serializes migrate:fresh + db:seed across concurrently booting containers
     * via a Postgres advisory lock. Without this, a rolling deploy or autoscale
     * event that starts two containers at once lets both run migrate:fresh
     * against the same database — one instance's schema drop/recreate can wipe
     * out a table the other just created moments earlier, mid-migration.
     *
     * Only the first container to grab the lock runs migrate:fresh + db:seed;
     * any other container that boots concurrently waits for the lock and then
     * skips straight to serving instead of repeating the destructive reset.
     */
    protected $signature = 'deploy:fresh-seed';

    protected $description = 'Run migrate:fresh + db:seed under a DB advisory lock to prevent concurrent boot races.';

    private const LOCK_KEY = 727100001;

    public function handle(): int
    {
        if (DB::getDriverName() !== 'pgsql') {
            $this->call('migrate:fresh', ['--force' => true]);
            $this->call('db:seed', ['--force' => true]);

            return self::SUCCESS;
        }

        $gotLock = (bool) DB::selectOne('select pg_try_advisory_lock(?)::int as locked', [self::LOCK_KEY])->locked;

        if (! $gotLock) {
            $this->info('Another instance is already running migrate:fresh + db:seed; waiting for it to finish...');
            DB::select('select pg_advisory_lock(?)', [self::LOCK_KEY]);
            DB::select('select pg_advisory_unlock(?)', [self::LOCK_KEY]);
            $this->info('Done waiting; skipping migrate:fresh/db:seed for this instance.');

            return self::SUCCESS;
        }

        try {
            $this->info('Lock acquired, running migrate:fresh + db:seed...');
            Artisan::call('migrate:fresh', ['--force' => true], $this->output);
            Artisan::call('db:seed', ['--force' => true], $this->output);
        } finally {
            DB::select('select pg_advisory_unlock(?)', [self::LOCK_KEY]);
        }

        return self::SUCCESS;
    }
}
