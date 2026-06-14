<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTripRequest;
use App\Models\Trip;
use App\Models\Jadwal;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\DetailTrip;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'ready'); // default to ready

        // Validate status input to prevent sql issues
        if (!in_array($status, ['new', 'ready', 'on_trip', 'completed', 'cancelled'])) {
            $status = 'ready';
        }

        $trips = Trip::query()
            ->with(['driver', 'jadwal.rute', 'detailTrips.booking.pelanggan', 'armada'])
            ->when($status, function ($query) use ($status) {
                $query->where('status_trip', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('driver', function ($drvQuery) use ($search) {
                        $drvQuery->where('nama_driver', 'like', "%{$search}%");
                    })->orWhereHas('armada', function ($armQuery) use ($search) {
                        $armQuery->where('nama_mobil', 'like', "%{$search}%")
                                 ->orWhere('nomor_plat', 'like', "%{$search}%");
                    })->orWhereHas('jadwal.rute', function ($ruteQuery) use ($search) {
                        $ruteQuery->where('asal', 'like', "%{$search}%")
                                  ->orWhere('tujuan', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        // Get count of trips for badge indicators
        $counts = [
            'new' => Trip::where('status_trip', 'new')->count(),
            'ready' => Trip::where('status_trip', 'ready')->count(),
            'on_trip' => Trip::where('status_trip', 'on_trip')->count(),
            'completed' => Trip::where('status_trip', 'completed')->count(),
            'cancelled' => Trip::where('status_trip', 'cancelled')->count(),
        ];

        // Get bookings waiting to be assigned (status: dikonfirmasi)
        $bookings = Booking::where('status_booking', 'dikonfirmasi')
            ->with(['pelanggan', 'jadwal.rute'])
            ->latest()
            ->get();

        return view('admin.trips.index', compact('trips', 'bookings', 'status', 'search', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch active schedules
        $schedules = Jadwal::where('status_jadwal', 'aktif')
            ->with('rute')
            ->latest()
            ->get();

        // Fetch active drivers
        $drivers = Driver::where('status_driver', 'aktif')
            ->latest()
            ->get();

        // Fetch active armadas
        $armadas = \App\Models\Armada::where('status_armada', 'aktif')
            ->latest()
            ->get();

        return view('admin.trips.create', compact('schedules', 'drivers', 'armadas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $trip = Trip::create([
            'jadwal_id' => $request->jadwal_id,
            'driver_id' => $request->driver_id,
            'armada_id' => $request->armada_id,
            'status_trip' => 'ready',
        ]);

        return redirect()
            ->route('admin.trips.index')
            ->with('success', 'Trip baru (TRP-' . str_pad($trip->id, 3, '0', STR_PAD_LEFT) . ') berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        $trip->load(['driver', 'jadwal.rute', 'detailTrips.booking.pelanggan', 'armada']);

        // Fetch drivers that are active, and:
        // - Either they don't have any active trip (ready/on_trip) OR they are the current driver of this trip.
        $drivers = Driver::with('armada')->where('status_driver', 'aktif')
            ->where(function($query) use ($trip) {
                $query->whereDoesntHave('trips', function($q) {
                    $q->whereIn('status_trip', ['ready', 'on_trip']);
                })->orWhere('id', $trip->driver_id);
            })
            ->latest()
            ->get();

        // Fetch armadas that are active, and:
        // - Either they don't have any active trip (ready/on_trip) OR they are the current armada of this trip.
        $armadas = \App\Models\Armada::where('status_armada', 'aktif')
            ->where(function($query) use ($trip) {
                $query->whereDoesntHave('trips', function($q) {
                    $q->whereIn('status_trip', ['ready', 'on_trip']);
                })->orWhere('id', $trip->armada_id);
            })
            ->latest()
            ->get();

        // Fetch bookings for the same schedule (jadwal_id) that have status 'dikonfirmasi'
        $availableBookings = Booking::where('status_booking', Booking::STATUS_DIKONFIRMASI)
            ->where('jadwal_id', $trip->jadwal_id)
            ->with('pelanggan')
            ->latest()
            ->get();

        return view('admin.trips.show', compact('trip', 'drivers', 'armadas', 'availableBookings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        return redirect()->route('admin.trips.show', $trip->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'armada_id' => 'nullable|exists:armada,id',
            'status_trip' => 'nullable|in:new,ready,on_trip,completed,cancelled',
        ]);

        $updateData = [];

        if ($request->has('driver_id')) {
            $driver = Driver::findOrFail($request->driver_id);

            if ($driver->status_driver === 'nonaktif') {
                return redirect()->back()->with('error', 'Driver tidak aktif.');
            }

            // check if driver is already assigned to another active trip
            $hasOtherActiveTrip = $driver->trips()
                ->where('id', '!=', $trip->id)
                ->whereIn('status_trip', ['ready', 'on_trip'])
                ->exists();

            if ($hasOtherActiveTrip) {
                return redirect()->back()->with('error', 'Driver sedang bertugas di trip lain.');
            }

            $updateData['driver_id'] = $request->driver_id;
        }

        if ($request->has('armada_id')) {
            $armada = \App\Models\Armada::findOrFail($request->armada_id);

            if ($armada->status_armada === 'nonaktif') {
                return redirect()->back()->with('error', 'Armada tidak aktif.');
            }

            // check if armada is already assigned to another active trip
            $hasOtherActiveTrip = \App\Models\Trip::where('id', '!=', $trip->id)
                ->where('armada_id', $request->armada_id)
                ->whereIn('status_trip', ['ready', 'on_trip'])
                ->exists();

            if ($hasOtherActiveTrip) {
                return redirect()->back()->with('error', 'Armada sedang digunakan di trip lain.');
            }

            $updateData['armada_id'] = $request->armada_id;
        }

        if ($request->has('status_trip')) {
            $status = $request->status_trip;
            $updateData['status_trip'] = $status;

            if ($status === 'on_trip' && !$trip->started_at) {
                $updateData['started_at'] = now();
            } elseif ($status === 'completed') {
                $updateData['completed_at'] = now();

                // Set all bookings to completed
                DB::transaction(function() use ($trip) {
                    foreach ($trip->detailTrips as $detail) {
                        if ($detail->booking) {
                            $detail->booking->update(['status_booking' => Booking::STATUS_COMPLETED]);
                        }
                    }
                });
            } elseif ($status === 'cancelled') {
                // Revert all bookings to dikonfirmasi
                DB::transaction(function() use ($trip) {
                    foreach ($trip->detailTrips as $detail) {
                        if ($detail->booking) {
                            $detail->booking->update(['status_booking' => Booking::STATUS_DIKONFIRMASI]);
                        }
                    }
                });
            }
        }

        $trip->update($updateData);

        return redirect()->route('admin.trips.show', $trip->id)
            ->with('success', 'Trip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        DB::transaction(function() use ($trip) {
            // Revert all bookings in this trip to dikonfirmasi
            foreach ($trip->detailTrips as $detail) {
                if ($detail->booking) {
                    $detail->booking->update(['status_booking' => Booking::STATUS_DIKONFIRMASI]);
                }
            }
            $trip->delete();
        });

        return redirect()->route('admin.trips.index')
            ->with('success', 'Trip berhasil dihapus dan antrean booking dikembalikan.');
    }

    /**
     * Assign a booking to the specified trip.
     */
    public function assignBooking(Request $request, Trip $trip)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->jadwal_id !== $trip->jadwal_id) {
            return redirect()->back()->with('error', 'Jadwal booking tidak sesuai dengan jadwal trip.');
        }

        if ($booking->status_booking === Booking::STATUS_ASSIGNED_TO_TRIP) {
            return redirect()->back()->with('error', 'Booking sudah ditugaskan ke trip lain.');
        }

        if ($booking->status_booking !== Booking::STATUS_DIKONFIRMASI) {
            return redirect()->back()->with('error', 'Booking belum dikonfirmasi pembayarannya.');
        }

        // Check sisa kapasitas
        $currentPax = $trip->detailTrips->sum(function($dt) {
            return $dt->booking ? $dt->booking->jumlah_penumpang : 0;
        });
        $capacity = $trip->armada ? $trip->armada->kapasitas : 5;
        $remainingSeats = $capacity - $currentPax;

        if ($booking->jumlah_penumpang > $remainingSeats) {
            return redirect()->back()->with('error', 'Kapasitas mobil tidak mencukupi untuk penumpang baru.');
        }

        DB::transaction(function() use ($trip, $booking) {
            $trip->detailTrips()->create([
                'booking_id' => $booking->id,
                'status_jemput' => 'belum',
                'status_antar' => 'belum',
            ]);

            $booking->update([
                'status_booking' => Booking::STATUS_ASSIGNED_TO_TRIP,
            ]);
        });

        return redirect()->route('admin.trips.show', $trip->id)
            ->with('success', 'Booking ' . $booking->kode_booking . ' berhasil ditugaskan ke trip.');
    }

    /**
     * Remove a booking from the specified trip.
     */
    public function removeBooking(Trip $trip, DetailTrip $detailTrip)
    {
        // Safety check to make sure the detailTrip belongs to this trip
        if ($detailTrip->trip_id !== $trip->id) {
            return redirect()->back()->with('error', 'Data manifes tidak cocok dengan trip ini.');
        }

        $booking = $detailTrip->booking;

        DB::transaction(function() use ($detailTrip, $booking) {
            $detailTrip->delete();

            if ($booking) {
                $booking->update([
                    'status_booking' => Booking::STATUS_DIKONFIRMASI,
                ]);
            }
        });

        return redirect()->route('admin.trips.show', $trip->id)
            ->with('success', 'Booking berhasil dikeluarkan dari trip.');
    }
}
