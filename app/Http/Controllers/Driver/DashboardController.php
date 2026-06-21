<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\DetailTrip;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $driver = $user->driver;

        if (!$driver) {
            return view('driver.dashboard', [
                'driver' => null,
                'activeTrip' => null,
                'stats' => [
                    'total_trips' => 0,
                    'total_passengers' => 0,
                    'total_revenue' => 0
                ]
            ]);
        }

        // Fetch active trip (status is ready or on_trip)
        $activeTrip = Trip::where('driver_id', $driver->id)
            ->whereIn('status_trip', [Trip::STATUS_READY, Trip::STATUS_ON_TRIP])
            ->with(['jadwal.rute', 'detailTrips.booking.pelanggan', 'armada'])
            ->first();

        // Calculate statistics from completed trips
        $completedTrips = Trip::where('driver_id', $driver->id)
            ->where('status_trip', Trip::STATUS_COMPLETED)
            ->get();

        $totalTrips = $completedTrips->count();

        // Sum passengers from completed trips
        $totalPassengers = DetailTrip::whereHas('trip', function ($q) use ($driver) {
            $q->where('driver_id', $driver->id)->where('status_trip', Trip::STATUS_COMPLETED);
        })
        ->with('booking')
        ->get()
        ->sum(function ($detail) {
            return $detail->booking ? $detail->booking->jumlah_penumpang : 0;
        });

        // Sum revenue (pelunasan = total_harga - 50000 DP) from completed trips
        $totalRevenue = DetailTrip::whereHas('trip', function ($q) use ($driver) {
            $q->where('driver_id', $driver->id)->where('status_trip', Trip::STATUS_COMPLETED);
        })
        ->with('booking')
        ->get()
        ->sum(function ($detail) {
            if (!$detail->booking) return 0;
            return max(0, $detail->booking->total_harga - 50000);
        });

        $stats = [
            'total_trips' => $totalTrips,
            'total_passengers' => $totalPassengers,
            'total_revenue' => $totalRevenue
        ];

        return view('driver.dashboard', compact('driver', 'activeTrip', 'stats'));
    }
}
