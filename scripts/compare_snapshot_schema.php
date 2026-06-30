<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dir = database_path('exports');
$manifest = json_decode(file_get_contents($dir . '/_manifest.json'), true);

foreach ($manifest['tables'] as $t) {
    $name = $t['name'];
    $file = $dir . '/' . $name . '.json';
    if (! is_file($file)) {
        continue;
    }
    $rows = json_decode(file_get_contents($file), true);
    if (empty($rows)) {
        continue;
    }
    if (! Illuminate\Support\Facades\Schema::hasTable($name)) {
        echo "MISSING TABLE: {$name}\n";
        continue;
    }
    $exportCols = array_keys($rows[0]);
    $dbCols = Illuminate\Support\Facades\Schema::getColumnListing($name);
    $missing = array_diff($exportCols, $dbCols);
    if ($missing) {
        echo "{$name}: " . implode(', ', $missing) . "\n";
    }
}
