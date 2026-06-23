<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Booking;
use App\Http\Requests\SearchJadwalRequest;

class JadwalPublicController extends Controller
{
    /**
     * Display public schedules with optional filtering.
     */
    public function index(SearchJadwalRequest $request)
    {
        $validated = $request->validated();

        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $query = Jadwal::with('rute')
            ->withSum(['bookings as booked_seats' => function ($q) {
                $q->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
            }], 'jumlah_penumpang')
            ->aktif()
            ->whereDoesntHave('trips', function ($query) {
                $query->whereIn('status_trip', [\App\Models\Trip::STATUS_ON_TRIP, \App\Models\Trip::STATUS_COMPLETED]);
            })
            ->where(function ($q) use ($today, $currentTime) {
                $q->where('tanggal_keberangkatan', '>', $today)
                  ->orWhere(function ($sq) use ($today, $currentTime) {
                      $sq->where('tanggal_keberangkatan', $today)
                         ->where('jam_berangkat', '>=', $currentTime);
                  });
            });

        if (!empty($validated['asal'])) {
            $query->whereHas('rute', function ($q) use ($validated) {
                $q->where('asal', 'like', '%' . $validated['asal'] . '%');
            });
        }

        if (!empty($validated['tujuan'])) {
            $query->whereHas('rute', function ($q) use ($validated) {
                $q->where('tujuan', 'like', '%' . $validated['tujuan'] . '%');
            });
        }

        if (!empty($validated['tanggal'])) {
            $query->whereDate('tanggal_keberangkatan', $validated['tanggal']);
        }

        $schedules = $query->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        return view('public.jadwal.index', compact('schedules'));
    }

    /**
     * Get active schedules with available seats.
     */
    public function available()
    {
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $schedules = Jadwal::with('rute')
            ->aktif()
            ->withSum(['bookings as booked_seats' => function ($q) {
                $q->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
            }], 'jumlah_penumpang')
            ->whereDoesntHave('trips', function ($query) {
                $query->whereIn('status_trip', [\App\Models\Trip::STATUS_ON_TRIP, \App\Models\Trip::STATUS_COMPLETED]);
            })
            ->where(function ($q) use ($today, $currentTime) {
                $q->where('tanggal_keberangkatan', '>', $today)
                  ->orWhere(function ($sq) use ($today, $currentTime) {
                      $sq->where('tanggal_keberangkatan', $today)
                         ->where('jam_berangkat', '>=', $currentTime);
                  });
            })
            ->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get()
            ->map(function ($schedule) {
                $availableSeats = $schedule->kuota - ($schedule->booked_seats ?? 0);
                return [
                    'id' => $schedule->id,
                    'tanggal' => $schedule->tanggal_keberangkatan->format('Y-m-d'),
                    'tanggal_formatted' => $schedule->tanggal_keberangkatan->format('d M Y'),
                    'shift' => ucfirst($schedule->shift),
                    'jam_berangkat' => $schedule->jam_berangkat->format('H:i'),
                    'asal' => $schedule->rute->asal,
                    'tujuan' => $schedule->rute->tujuan,
                    'tarif' => $schedule->rute->tarif,
                    'tarif_formatted' => 'Rp ' . number_format($schedule->rute->tarif, 0, ',', '.'),
                    'kuota' => $schedule->kuota,
                    'booked_seats' => $schedule->booked_seats ?? 0,
                    'available_seats' => max(0, $availableSeats),
                ];
            });

        return response()->json($schedules);
    }

    /**
     * Check available kuota for a specific schedule.
     */
    public function checkKuota($id)
    {
        $schedule = Jadwal::withSum(['bookings as booked_seats' => function ($q) {
            $q->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
        }], 'jumlah_penumpang')->findOrFail($id);

        $availableSeats = $schedule->kuota - ($schedule->booked_seats ?? 0);

        return response()->json([
            'id' => $schedule->id,
            'kuota' => $schedule->kuota,
            'booked_seats' => $schedule->booked_seats ?? 0,
            'available_seats' => max(0, $availableSeats),
        ]);
    }
}
