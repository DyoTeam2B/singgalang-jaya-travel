<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTripRequest;
use App\Models\Trip;
use App\Models\Jadwal;
use App\Models\Driver;
use App\Models\Booking;
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
            ->with(['driver', 'jadwal.rute', 'detailTrips.booking.pelanggan'])
            ->when($status, function ($query) use ($status) {
                $query->where('status_trip', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('driver', function ($drvQuery) use ($search) {
                        $drvQuery->where('nama_driver', 'like', "%{$search}%")
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

        return view('admin.trips.create', compact('schedules', 'drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $trip = Trip::create([
            'jadwal_id' => $request->jadwal_id,
            'driver_id' => $request->driver_id,
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
        return redirect()->route('admin.trips.index')
            ->with('info', 'Detail Trip akan tersedia pada Sprint 2.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        return redirect()->route('admin.trips.index')
            ->with('info', 'Fitur edit Trip akan tersedia pada Sprint 2.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        return redirect()->route('admin.trips.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        return redirect()->route('admin.trips.index')
            ->with('info', 'Fitur hapus Trip akan tersedia pada Sprint 2.');
    }
}
