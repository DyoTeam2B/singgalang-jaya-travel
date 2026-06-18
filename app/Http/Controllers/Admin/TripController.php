<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTripRequest;
use App\Http\Requests\Admin\AssignBookingRequest;
use App\Models\Trip;
use App\Models\Jadwal;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\DetailTrip;
use App\Services\BookingWhatsappNotificationService;
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
        $bookings = Booking::where('status_booking', Booking::STATUS_DIKONFIRMASI)
            ->with(['pelanggan', 'jadwal.rute'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($bookingQuery) use ($search) {
                    $bookingQuery->where('kode_booking', 'like', "%{$search}%")
                        ->orWhereHas('pelanggan', function ($pelangganQuery) use ($search) {
                            $pelangganQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('no_hp', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        $assignableTrips = Trip::whereIn('jadwal_id', $bookings->pluck('jadwal_id')->unique())
            ->whereIn('status_trip', [Trip::STATUS_NEW, Trip::STATUS_READY])
            ->with(['driver', 'armada', 'jadwal.rute', 'detailTrips.booking'])
            ->get()
            ->groupBy('jadwal_id');

        $bookings->each(function (Booking $booking) use ($assignableTrips) {
            $booking->setAttribute('assignable_trips', $assignableTrips
                ->get($booking->jadwal_id, collect())
                ->map(function (Trip $trip) {
                    $currentPax = $trip->detailTrips->sum(fn ($detail) => $detail->booking?->jumlah_penumpang ?? 0);

                    return [
                        'id' => $trip->id,
                        'driver_name' => $trip->driver->nama_driver ?? 'Belum Ditugaskan',
                        'plate' => $trip->armada->nomor_plat ?? '-',
                        'capacity' => $trip->armada->kapasitas ?? 5,
                        'pax' => $currentPax,
                        'departure_date' => $trip->jadwal->tanggal_keberangkatan->format('d M Y'),
                        'shift' => ucfirst($trip->jadwal->shift),
                        'time' => $trip->jadwal->jam_berangkat->format('H:i'),
                    ];
                })
                ->values());
        });

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

        // Fetch active drivers with their assigned armada.
        $drivers = Driver::with('armada')
            ->where('status_driver', 'aktif')
            ->whereHas('armada', fn ($query) => $query->where('status_armada', 'aktif'))
            ->latest()
            ->get();

        return view('admin.trips.create', compact('schedules', 'drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $driver = Driver::with('armada')->findOrFail($request->validated()['driver_id']);

        $trip = Trip::create([
            'jadwal_id' => $request->jadwal_id,
            'driver_id' => $driver->id,
            'armada_id' => $driver->armada_id,
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
        $trip->load(['driver.armada', 'jadwal.rute', 'detailTrips.booking.pelanggan', 'armada']);

        $jadwal = $trip->jadwal;

        // Driver hanya dianggap sibuk jika sudah punya trip pada tanggal dan shift yang sama.
        $drivers = Driver::with('armada')->where('status_driver', 'aktif')
            ->whereHas('armada', fn ($query) => $query->where('status_armada', 'aktif'))
            ->where(function ($query) use ($trip, $jadwal) {
                $query->whereDoesntHave('trips', function ($tripQuery) use ($trip, $jadwal) {
                    $this->whereConflictingSchedule($tripQuery, $jadwal, $trip->id);
                })->orWhere('id', $trip->driver_id);
            })
            ->where(function ($query) use ($trip, $jadwal) {
                $query->whereDoesntHave('armada.trips', function ($tripQuery) use ($trip, $jadwal) {
                    $this->whereConflictingSchedule($tripQuery, $jadwal, $trip->id);
                })->orWhere('id', $trip->driver_id);
            })
            ->latest()
            ->get();

        // Fetch bookings for the same schedule (jadwal_id) that have status 'dikonfirmasi'
        $availableBookings = Booking::where('status_booking', Booking::STATUS_DIKONFIRMASI)
            ->where('jadwal_id', $trip->jadwal_id)
            ->with('pelanggan')
            ->latest()
            ->get();

        return view('admin.trips.show', compact('trip', 'drivers', 'availableBookings'));
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
            'status_trip' => 'nullable|in:new,ready,on_trip,completed,cancelled',
        ]);

        $trip->loadMissing('jadwal');
        $updateData = [];

        if ($request->has('driver_id')) {
            $driver = Driver::with('armada')->findOrFail($request->driver_id);

            if ($driver->status_driver === 'nonaktif') {
                return redirect()->back()->with('error', 'Driver tidak aktif.');
            }

            if (! $driver->armada) {
                return redirect()->back()->with('error', 'Driver belum memiliki armada.');
            }

            if ($driver->armada->status_armada === 'nonaktif') {
                return redirect()->back()->with('error', 'Armada milik driver tidak aktif.');
            }

            $hasScheduleConflict = $driver->trips()
                ->where(function ($query) use ($trip) {
                    $this->whereConflictingSchedule($query, $trip->jadwal, $trip->id);
                })
                ->exists();

            if ($hasScheduleConflict) {
                return redirect()->back()->with('error', 'Driver sudah bertugas pada tanggal dan shift yang sama.');
            }

            $hasArmadaConflict = Trip::where('armada_id', $driver->armada_id)
                ->where(function ($query) use ($trip) {
                    $this->whereConflictingSchedule($query, $trip->jadwal, $trip->id);
                })
                ->exists();

            if ($hasArmadaConflict) {
                return redirect()->back()->with('error', 'Armada milik driver sudah digunakan pada tanggal dan shift yang sama.');
            }

            $updateData['driver_id'] = $driver->id;
            $updateData['armada_id'] = $driver->armada_id;
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
    public function assignBooking(
        AssignBookingRequest $request,
        Trip $trip,
        BookingWhatsappNotificationService $whatsappNotificationService
    )
    {
        $booking = Booking::findOrFail($request->validated()['booking_id']);

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

        $booking->load(['pelanggan', 'jadwal.rute']);
        $trip->load(['driver', 'armada', 'jadwal.rute']);

        $whatsappNotificationService->sendTripAssignedToCustomer($booking, $trip);
        $whatsappNotificationService->sendTripAssignedToDriver($booking, $trip);

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

    /**
     * Batasi konflik driver/armada pada tanggal dan shift yang sama saja.
     */
    private function whereConflictingSchedule($query, Jadwal $jadwal, ?int $ignoreTripId = null): void
    {
        if ($ignoreTripId !== null) {
            $query->where('trips.id', '!=', $ignoreTripId);
        }

        $query->where('status_trip', '!=', Trip::STATUS_CANCELLED)
            ->whereHas('jadwal', function ($jadwalQuery) use ($jadwal) {
                $jadwalQuery->whereDate('tanggal_keberangkatan', $jadwal->tanggal_keberangkatan->toDateString())
                    ->where('shift', $jadwal->shift);
            });
    }
}
