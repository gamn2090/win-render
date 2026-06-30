<?php

/**
 * Uses .env.local (Postgres/Render) merged over .env temporarily, creates vendor@test.com,
 * then restores .env. Run: php scripts/create_vendor_test_on_remote_db.php
 */

declare(strict_types=1);

use App\Models\Profile;
use App\Models\Vendor;
use App\Models\VendorRanking;
use App\Models\VendorTypes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$basePath = dirname(__DIR__);
$envPath = $basePath . '/.env';
$localPath = $basePath . '/.env.local';
$backupPath = $basePath . '/.env.backup_before_remote_vendor_' . date('Ymd_His');

if (!is_readable($localPath)) {
    fwrite(STDERR, "Missing or unreadable .env.local\n");
    exit(1);
}
if (!is_readable($envPath)) {
    fwrite(STDERR, "Missing .env\n");
    exit(1);
}

$stripKeys = [
    'DB_CONNECTION',
    'DB_PORT',
    'DB_HOST',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'DB_SOCKET',
    'DATABASE_URL',
];

$lines = file($envPath, FILE_IGNORE_NEW_LINES);
$filtered = [];
foreach ($lines as $line) {
    $trim = ltrim($line);
    if ($trim === '' || str_starts_with($trim, '#')) {
        $filtered[] = $line;
        continue;
    }
    if (!str_contains($line, '=')) {
        $filtered[] = $line;
        continue;
    }
    $key = trim(explode('=', $line, 2)[0]);
    if (in_array($key, $stripKeys, true)) {
        continue;
    }
    $filtered[] = $line;
}

$merged = rtrim(implode("\n", $filtered)) . "\n\n" . trim(file_get_contents($localPath)) . "\n";

copy($envPath, $backupPath);
try {
    file_put_contents($envPath, $merged);
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    Artisan::call('config:clear');

    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
    } catch (\Throwable $e) {
        fwrite(STDERR, 'Database connection failed: ' . $e->getMessage() . "\n");
        exit(1);
    }

    if (VendorTypes::query()->doesntExist()) {
        VendorTypes::create([
            'type' => 'General',
            'icon' => 'default',
        ]);
        fwrite(STDERR, "Inserted minimal vendor_types row (table was empty).\n");
    }

    $typeId = VendorTypes::query()->orderBy('priority')->value('id');
    if (!$typeId) {
        fwrite(STDERR, "vendor_types still empty after seed attempt.\n");
        exit(1);
    }

    $v = Vendor::query()->updateOrCreate(
        ['email' => 'vendor@test.com'],
        [
            'first_name' => 'Test',
            'last_name' => 'Vendor',
            'business_name' => 'Negocio prueba',
            'type' => $typeId,
            'discount' => 100,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'location' => 'Ciudad',
        ]
    );

    Profile::query()->firstOrCreate(
        ['belongs_to' => $v->id, 'type' => 'vendor'],
        ['bio' => null, 'business_link' => null]
    );

    if (Schema::hasTable('vendor_rankings') && Schema::hasColumn('vendor_rankings', 'vendor_id')) {
        VendorRanking::query()->firstOrCreate(['vendor_id' => $v->id]);
    } else {
        fwrite(STDERR, "Skipped vendor_rankings (table/column missing on this DB).\n");
    }

    echo "OK vendor id={$v->id} email=vendor@test.com (password unchanged: password)\n";
    echo "Env backup file: {$backupPath}\n";
} finally {
    if (is_readable($backupPath)) {
        copy($backupPath, $envPath);
        $php = defined('PHP_BINARY') ? PHP_BINARY : 'php';
        passthru(
            escapeshellarg($php) . ' ' . escapeshellarg($basePath . DIRECTORY_SEPARATOR . 'artisan') . ' config:clear',
            $exitCode
        );
    }
}
