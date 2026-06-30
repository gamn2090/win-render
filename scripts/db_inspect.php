<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Connection used by Laravel ===\n";
$cfg = config('database.connections.' . config('database.default'));
echo " driver:  " . ($cfg['driver']   ?? '?') . "\n";
echo " host:    " . ($cfg['host']     ?? '?') . "\n";
echo " port:    " . ($cfg['port']     ?? '?') . "\n";
echo " db:      " . ($cfg['database'] ?? '?') . "\n";
echo " user:    " . ($cfg['username'] ?? '?') . "\n\n";

echo "=== DATABASES on this MySQL server ===\n";
$dbs = collect(DB::select('SHOW DATABASES'))->pluck('Database')->all();
foreach ($dbs as $db) {
    echo " - {$db}\n";
}

$candidates = array_values(array_filter($dbs, function ($d) {
    return preg_match('/(winetwork|windfront|wedding)/i', $d);
}));

echo "\n=== TABLE COUNTS (candidate DBs) ===\n";
foreach ($candidates as $db) {
    $count = DB::selectOne(
        "SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = ?",
        [$db]
    )->c;
    echo str_pad($db, 30) . " => {$count} tables\n";
}

echo "\n=== TABLE LIST in DB used by project ('" . $cfg['database'] . "') ===\n";
$tables = DB::select('SHOW TABLES');
$first  = array_keys((array) $tables[0])[0];
foreach ($tables as $t) {
    $arr = (array) $t;
    echo " - " . $arr[$first] . "\n";
}
echo "\nTOTAL: " . count($tables) . " tables\n";
