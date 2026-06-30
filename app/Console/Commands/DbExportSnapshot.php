<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DbExportSnapshot extends Command
{
    /**
     * Exports the current database into a portable JSON snapshot under
     * database/exports/snapshot.json (split per table). Designed to be
     * imported by SnapshotSeeder on any DB engine (MySQL/PostgreSQL/SQLite).
     */
    protected $signature = 'db:export-snapshot {--out=database/exports}';

    protected $description = 'Export all DB tables to JSON snapshots for portable seeding (MySQL/PostgreSQL).';

    /**
     * Tables that should never be exported (transient/runtime).
     */
    private array $skipTables = [
        'migrations',
        'sessions',
        'cache',
        'cache_locks',
        'failed_jobs',
        'jobs',
        'job_batches',
        'password_reset_tokens',
        'personal_access_tokens',
    ];

    public function handle(): int
    {
        $outDir = base_path($this->option('out'));
        if (!is_dir($outDir)) {
            mkdir($outDir, 0775, true);
        }

        $tables = $this->getAllTables();

        $manifest = [
            'generated_at' => now()->toIso8601String(),
            'driver' => DB::getDriverName(),
            'tables' => [],
        ];

        $totalRows = 0;

        foreach ($tables as $table) {
            if (in_array($table, $this->skipTables, true)) {
                $this->line("  - skipping {$table} (excluded)");
                continue;
            }

            if (!Schema::hasTable($table)) {
                continue;
            }

            $rows = DB::table($table)->get();
            $count = $rows->count();
            $totalRows += $count;

            $rowsArr = $rows->map(fn ($r) => (array) $r)->all();

            $file = "{$outDir}/{$table}.json";
            file_put_contents($file, json_encode($rowsArr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            $manifest['tables'][] = [
                'name' => $table,
                'rows' => $count,
                'file' => "{$table}.json",
            ];

            $this->info(sprintf("  + %-40s %6d rows", $table, $count));
        }

        file_put_contents("{$outDir}/_manifest.json", json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->newLine();
        $this->info("Snapshot written to: {$outDir}");
        $this->info("Total tables exported: " . count($manifest['tables']));
        $this->info("Total rows exported: {$totalRows}");

        return self::SUCCESS;
    }

    private function getAllTables(): array
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $dbName = config('database.connections.mysql.database');
            $rows = DB::select('SHOW TABLES');
            $key = "Tables_in_{$dbName}";
            return array_map(fn ($r) => $r->{$key}, $rows);
        }

        if ($driver === 'pgsql') {
            $rows = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' AND table_type = 'BASE TABLE'");
            return array_map(fn ($r) => $r->table_name, $rows);
        }

        if ($driver === 'sqlite') {
            $rows = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            return array_map(fn ($r) => $r->name, $rows);
        }

        return [];
    }
}
