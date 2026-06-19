<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Drivers ===" . PHP_EOL;
$drivers = DB::select('SELECT * FROM drivers');
foreach ($drivers as $d) {
    echo json_encode($d) . PHP_EOL;
}

echo PHP_EOL . "=== Trips ===" . PHP_EOL;
$trips = DB::select('SELECT * FROM trips');
foreach ($trips as $t) {
    echo json_encode($t) . PHP_EOL;
}

echo PHP_EOL . "=== Armada (if exists) ===" . PHP_EOL;
try {
    $armada = DB::select('SELECT * FROM armada');
    foreach ($armada as $a) {
        echo json_encode($a) . PHP_EOL;
    }
    if (empty($armada)) echo "(empty table)" . PHP_EOL;
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
