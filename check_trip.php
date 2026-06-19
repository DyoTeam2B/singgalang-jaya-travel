<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Trip;
use App\Models\Driver;

$trip = Trip::with(['driver', 'jadwal.rute', 'detailTrips.booking.pelanggan', 'armada'])->first();

if (!$trip) {
    echo "No trips found\n";
    exit;
}

echo "Trip ID: {$trip->id}\n";
echo "Driver ID: {$trip->driver_id}\n";
echo "Armada ID: {$trip->armada_id}\n";
echo "Jadwal ID: {$trip->jadwal_id}\n";
echo "Status: {$trip->status_trip}\n";
echo "Has Jadwal: " . ($trip->jadwal ? 'Yes' : 'No') . "\n";
echo "Has Rute: " . ($trip->jadwal && $trip->jadwal->rute ? 'Yes' : 'No') . "\n";
echo "Has Driver: " . ($trip->driver ? 'Yes' : 'No') . "\n";
echo "Has Armada: " . ($trip->armada ? 'Yes' : 'No') . "\n";

if ($trip->jadwal) {
    echo "Tanggal: " . $trip->jadwal->tanggal_keberangkatan . "\n";
    echo "Tanggal class: " . get_class($trip->jadwal->tanggal_keberangkatan) . "\n";
    echo "Format test: " . $trip->jadwal->tanggal_keberangkatan->format('d M Y') . "\n";
    echo "Shift: " . $trip->jadwal->shift . "\n";
    echo "Jam berangkat: " . $trip->jadwal->jam_berangkat . "\n";
    echo "Jam berangkat class: " . (is_object($trip->jadwal->jam_berangkat) ? get_class($trip->jadwal->jam_berangkat) : gettype($trip->jadwal->jam_berangkat)) . "\n";
}

// Test the Driver::with('armada') query from show method
echo "\n--- Testing driver query from show() ---\n";
try {
    $drivers = Driver::with('armada')->where('status_driver', 'aktif')
        ->where(function($query) use ($trip) {
            $query->whereDoesntHave('trips', function($q) {
                $q->whereIn('status_trip', ['ready', 'on_trip']);
            })->orWhere('id', $trip->driver_id);
        })
        ->latest()
        ->get();
    echo "Drivers found: " . $drivers->count() . "\n";
    foreach ($drivers as $driver) {
        echo "  - {$driver->nama_driver} (armada: " . ($driver->armada ? $driver->armada->nomor_plat : 'none') . ")\n";
    }
} catch (\Exception $e) {
    echo "ERROR in driver query: " . $e->getMessage() . "\n";
}

echo "\n--- Testing the full show view rendering ---\n";
try {
    $trip->load(['driver', 'jadwal.rute', 'detailTrips.booking.pelanggan', 'armada']);
    
    $drivers = Driver::with('armada')->where('status_driver', 'aktif')
        ->where(function($query) use ($trip) {
            $query->whereDoesntHave('trips', function($q) {
                $q->whereIn('status_trip', ['ready', 'on_trip']);
            })->orWhere('id', $trip->driver_id);
        })
        ->latest()
        ->get();

    $armadas = \App\Models\Armada::where('status_armada', 'aktif')
        ->where(function($query) use ($trip) {
            $query->whereDoesntHave('trips', function($q) {
                $q->whereIn('status_trip', ['ready', 'on_trip']);
            })->orWhere('id', $trip->armada_id);
        })
        ->latest()
        ->get();

    $availableBookings = \App\Models\Booking::where('status_booking', \App\Models\Booking::STATUS_DIKONFIRMASI)
        ->where('jadwal_id', $trip->jadwal_id)
        ->with('pelanggan')
        ->latest()
        ->get();

    echo "All data loaded successfully.\n";
    echo "Drivers: {$drivers->count()}, Armadas: {$armadas->count()}, Available Bookings: {$availableBookings->count()}\n";
    
    // Try rendering the view
    $html = view('admin.trips.show', compact('trip', 'drivers', 'armadas', 'availableBookings'))->render();
    echo "View rendered successfully! Length: " . strlen($html) . " bytes\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
