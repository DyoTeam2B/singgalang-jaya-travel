<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ALL ARMADA ===\n";
foreach (App\Models\Armada::all() as $a) {
    echo "Armada ID: {$a->id}, Nama: {$a->nama_mobil}, Plat: {$a->nomor_plat}, Kapasitas: {$a->kapasitas}, Status: {$a->status_armada}\n";
}

echo "\n=== ALL BOOKINGS ===\n";
foreach (App\Models\Booking::all() as $b) {
    echo "Booking ID: {$b->id}, Kode: {$b->kode_booking}, Jadwal ID: {$b->jadwal_id}, Penumpang: {$b->jumlah_penumpang}, Status: {$b->status_booking}\n";
}

echo "\n=== ALL TRIPS ===\n";
foreach (App\Models\Trip::all() as $t) {
    $currentPax = $t->detailTrips->sum(fn ($detail) => $detail->booking?->jumlah_penumpang ?? 0);
    $capacity = $t->armada?->kapasitas ?? 5;
    echo "Trip ID: {$t->id}, Jadwal ID: {$t->jadwal_id}, Driver ID: {$t->driver_id}, Armada ID: {$t->armada_id}, Status: {$t->status_trip}, Okupansi: {$currentPax}/{$capacity}\n";
}

echo "\n=== ALL DETAIL TRIPS ===\n";
foreach (App\Models\DetailTrip::all() as $dt) {
    echo "DetailTrip ID: {$dt->id}, Trip ID: {$dt->trip_id}, Booking ID: {$dt->booking_id}\n";
}
