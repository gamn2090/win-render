<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$dumpPath = __DIR__ . '/../DB_BACKUP_COPY/winetwork_main_backup_BD_COPY.sql';

$dumpSchema = [];
$current    = null;
$buf        = '';
$fh         = fopen($dumpPath, 'r');
while (($line = fgets($fh)) !== false) {
    if ($current === null && preg_match('/^CREATE TABLE `([^`]+)`/i', $line, $m)) {
        $current = $m[1];
        $buf     = $line;
        continue;
    }
    if ($current !== null) {
        $buf .= $line;
        if (preg_match('/^\)\s+ENGINE=/i', $line)) {
            preg_match_all('/^\s+`([^`]+)`/m', $buf, $cm);
            $dumpSchema[$current] = $cm[1];
            $current              = null;
            $buf                  = '';
        }
    }
}
fclose($fh);

$localTables = collect(DB::select('SHOW TABLES'))
    ->map(fn($t) => array_values((array) $t)[0])
    ->all();

$localSchema = [];
foreach ($localTables as $t) {
    $cols = collect(DB::select("SHOW COLUMNS FROM `{$t}`"))->pluck('Field')->all();
    $localSchema[$t] = $cols;
}

echo "=== TABLE-LEVEL COMPARISON ===\n";
$dumpTables  = array_keys($dumpSchema);
$localOnly   = array_diff($localTables, $dumpTables);
$dumpOnly    = array_diff($dumpTables, $localTables);
$inBoth      = array_intersect($localTables, $dumpTables);

echo "Tables in dump:  " . count($dumpTables)  . "\n";
echo "Tables in local: " . count($localTables) . "\n";
echo "In both:         " . count($inBoth)      . "\n\n";

if ($localOnly) {
    echo "+ LOCAL has these EXTRA tables (not in dump):\n";
    foreach ($localOnly as $t) echo "    + {$t}\n";
}
if ($dumpOnly) {
    echo "\n- LOCAL is MISSING these tables (in dump):\n";
    foreach ($dumpOnly as $t) echo "    - {$t}\n";
}

echo "\n=== COLUMN-LEVEL DIFFERENCES (tables in both) ===\n";
$anyDiff = false;
foreach ($inBoth as $t) {
    $missing = array_values(array_diff($dumpSchema[$t],  $localSchema[$t]));
    $extra   = array_values(array_diff($localSchema[$t], $dumpSchema[$t]));
    if ($missing || $extra) {
        $anyDiff = true;
        echo "\n[{$t}]\n";
        if ($missing) echo "    - missing in local: " . implode(', ', $missing) . "\n";
        if ($extra)   echo "    + extra in local:   " . implode(', ', $extra)   . "\n";
    }
}
if (!$anyDiff) {
    echo "All shared tables have identical column lists.\n";
}

echo "\n=== ROW COUNT SPOT-CHECK (key tables) ===\n";
$checks = ['users','vendors','pairings','vendor_connections','chat_conversations','chat_messages','profiles','inquiries'];
foreach ($checks as $t) {
    if (!in_array($t, $localTables, true)) continue;
    $c = DB::selectOne("SELECT COUNT(*) AS c FROM `{$t}`")->c;
    echo str_pad($t, 25) . " => {$c} rows\n";
}
