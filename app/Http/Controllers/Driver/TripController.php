<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\DetailTrip;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    /**
     * Display a listing of the completed / past trips.
     */
    public function index()
    {
        $driver = Auth::user()->driver;

        if (!$driver) {
            return redirect()->route('driver.dashboard')->with('error', 'Profil driver belum dilengkapi.');
        }

        $trips = Trip::where('driver_id', $driver->id)
            ->with(['jadwal.rute', 'detailTrips.booking.pelanggan', 'armada'])
            ->latest()
            ->paginate(10);

        // Calculate statistics for index display
        $completedTrips = Trip::where('driver_id', $driver->id)
            ->where('status_trip', Trip::STATUS_COMPLETED)
            ->get();

        $totalTrips = $completedTrips->count();

        $totalPassengers = DetailTrip::whereHas('trip', function ($q) use ($driver) {
            $q->where('driver_id', $driver->id)->where('status_trip', Trip::STATUS_COMPLETED);
        })
        ->with('booking')
        ->get()
        ->sum(function ($detail) {
            return $detail->booking ? $detail->booking->jumlah_penumpang : 0;
        });

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

        return view('driver.trips.index', compact('trips', 'stats'));
    }

    /**
     * Display the specified trip details.
     */
    public function show(Trip $trip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        $trip->load(['jadwal.rute', 'detailTrips.booking.pelanggan', 'armada']);

        return view('driver.trips.show', compact('trip'));
    }

    /**
     * Start the trip.
     */
    public function start(Trip $trip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($trip->status_trip !== Trip::STATUS_READY) {
            return redirect()->back()->with('error', 'Trip tidak berada dalam status siap berangkat.');
        }

        $trip->update([
            'status_trip' => Trip::STATUS_ON_TRIP,
            'started_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Trip berhasil dimulai. Selamat bertugas!');
    }

    /**
     * Mark passenger as picked up.
     */
    public function pickup(Trip $trip, DetailTrip $detailTrip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id || $detailTrip->trip_id !== $trip->id) {
            abort(403, 'Unauthorized action.');
        }

        $detailTrip->update([
            'status_jemput' => 'sudah_dijemput',
            'picked_up_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Penumpang ' . ($detailTrip->booking->pelanggan->nama ?? '') . ' berhasil ditandai sudah naik.');
    }

    /**
     * Mark passenger as dropped off and verify payment.
     */
    public function dropoff(Trip $trip, DetailTrip $detailTrip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id || $detailTrip->trip_id !== $trip->id) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($detailTrip) {
            // Update dropoff status
            $detailTrip->update([
                'status_antar' => 'sudah_diantar',
                'dropped_off_at' => now(),
            ]);

            // Automatically confirm pelunasan payment if not already paid
            $booking = $detailTrip->booking;
            if ($booking) {
                $hasPelunasan = Pembayaran::where('booking_id', $booking->id)
                    ->where('jenis_pembayaran', Pembayaran::JENIS_PELUNASAN)
                    ->where('status_pembayaran', Pembayaran::STATUS_TERVERIFIKASI)
                    ->exists();

                if (!$hasPelunasan) {
                    $remaining = max(0, $booking->total_harga - 50000);
                    Pembayaran::create([
                        'booking_id' => $booking->id,
                        'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
                        'jumlah_bayar' => $remaining,
                        'metode_pembayaran' => 'cash',
                        'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
                        'catatan' => 'Pelunasan diterima oleh driver saat pengantaran.',
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Penumpang ' . ($detailTrip->booking->pelanggan->nama ?? '') . ' telah sampai di tujuan.');
    }

    /**
     * Force confirm pelunasan payment.
     */
    public function confirmPayment(Trip $trip, DetailTrip $detailTrip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id || $detailTrip->trip_id !== $trip->id) {
            abort(403, 'Unauthorized action.');
        }

        $booking = $detailTrip->booking;
        if ($booking) {
            $hasPelunasan = Pembayaran::where('booking_id', $booking->id)
                ->where('jenis_pembayaran', Pembayaran::JENIS_PELUNASAN)
                ->where('status_pembayaran', Pembayaran::STATUS_TERVERIFIKASI)
                ->exists();

            if (!$hasPelunasan) {
                $remaining = max(0, $booking->total_harga - 50000);
                Pembayaran::create([
                    'booking_id' => $booking->id,
                    'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
                    'jumlah_bayar' => $remaining,
                    'metode_pembayaran' => 'cash',
                    'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
                    'catatan' => 'Pelunasan dikonfirmasi secara manual oleh driver.',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pembayaran pelunasan untuk penumpang ' . ($booking->pelanggan->nama ?? '') . ' berhasil dikonfirmasi.');
    }

    /**
     * Complete the trip.
     */
    public function complete(Trip $trip)
    {
        $driver = Auth::user()->driver;

        if (!$driver || $trip->driver_id !== $driver->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if there are any passengers who haven't been dropped off
        $hasUnreachedPassengers = $trip->detailTrips()
            ->where('status_antar', '!=', 'sudah_diantar')
            ->exists();

        if ($hasUnreachedPassengers) {
            return redirect()->back()->with('error', 'Semua penumpang harus diantar ke tujuan sebelum trip dapat diselesaikan.');
        }

        $trip->update([
            'status_trip' => Trip::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        return redirect()->route('driver.dashboard')->with('success', 'Trip berhasil diselesaikan. Terima kasih atas dedikasi Anda!');
    }
}
