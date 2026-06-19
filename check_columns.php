<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$columns = DB::select('SHOW COLUMNS FROM trips');
foreach ($columns as $col) {
    echo $col->Field . ' | ' . $col->Type . ' | ' . ($col->Null === 'YES' ? 'nullable' : 'not null') . ' | ' . ($col->Default ?? 'none') . PHP_EOL;
}

echo PHP_EOL . "--- Drivers table ---" . PHP_EOL;
$columns = DB::select('SHOW COLUMNS FROM drivers');
foreach ($columns as $col) {
    echo $col->Field . ' | ' . $col->Type . ' | ' . ($col->Null === 'YES' ? 'nullable' : 'not null') . ' | ' . ($col->Default ?? 'none') . PHP_EOL;
}
