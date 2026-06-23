<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalRequest;
use App\Http\Requests\Admin\UpdateJadwalRequest;
use App\Models\Jadwal;
use App\Models\Rute;
use App\Models\Trip;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'active'); // 'active' or 'history'
        $today = now()->toDateString();

        $jadwal = Jadwal::query()
            ->with(['rute'])
            ->withSum(['bookings' => function ($query) {
                $query->whereNotIn('status_booking', ['cancelled', 'expired']);
            }], 'jumlah_penumpang')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('rute', function ($q) use ($search) {
                    $q->where('asal', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%");
                })->orWhere('tanggal_keberangkatan', 'like', "%{$search}%")
                  ->orWhere('shift', 'like', "%{$search}%");
            })
            ->when($tab === 'history', function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    // Expired by date/time
                    $q->where('tanggal_keberangkatan', '<', $today)
                      ->orWhere(function ($q2) use ($today) {
                          $q2->where('tanggal_keberangkatan', '=', $today)
                             ->where('jam_berangkat', '<=', now()->toTimeString());
                      })
                      // Or has status nonaktif
                      ->orWhere('status_jadwal', Jadwal::STATUS_NONAKTIF)
                      // Or has active/completed trip
                      ->orWhereHas('trips', function ($tripQuery) {
                          $tripQuery->whereIn('status_trip', [Trip::STATUS_ON_TRIP, Trip::STATUS_COMPLETED]);
                      });
                });
            })
            ->when($tab === 'active', function ($query) use ($today) {
                // Must not be nonaktif
                $query->where('status_jadwal', '!=', Jadwal::STATUS_NONAKTIF)
                      // Must not have active/completed trip
                      ->whereDoesntHave('trips', function ($tripQuery) {
                          $tripQuery->whereIn('status_trip', [Trip::STATUS_ON_TRIP, Trip::STATUS_COMPLETED]);
                      })
                      // Must not be expired by date/time
                      ->where(function ($q) use ($today) {
                          $q->where('tanggal_keberangkatan', '>', $today)
                            ->orWhere(function ($q2) use ($today) {
                                $q2->where('tanggal_keberangkatan', '=', $today)
                                   ->where('jam_berangkat', '>', now()->toTimeString());
                            });
                      });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('admin.jadwal.index', compact('jadwal', 'search', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rute = Rute::latest()->get();
        return view('admin.jadwal.create', compact('rute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalRequest $request)
    {
        Jadwal::create($request->validated());

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal keberangkatan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        return redirect()->route('admin.jadwal.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        $rute = Rute::latest()->get();
        return view('admin.jadwal.edit', compact('jadwal', 'rute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalRequest $request, Jadwal $jadwal)
    {
        $data = $request->validated();
        
        // Calculate booked quota
        $booked = $jadwal->bookings()
            ->whereNotIn('status_booking', ['cancelled', 'expired'])
            ->sum('jumlah_penumpang');

        // Safety check if new capacity is lower than booked seats
        if ($data['kuota'] < $booked) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Kapasitas tidak boleh lebih kecil dari jumlah kursi yang sudah dipesan (' . $booked . ' kursi).');
        }

        // If capacity is reached, automatically update status to penuh
        if ($booked >= $data['kuota'] && $data['status_jadwal'] === 'aktif') {
            $data['status_jadwal'] = 'penuh';
        }

        $jadwal->update($data);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Data jadwal keberangkatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        // Safety check if schedule has any bookings
        if ($jadwal->bookings()->exists()) {
            return redirect()
                ->route('admin.jadwal.index')
                ->with('error', 'Jadwal tidak dapat dihapus karena memiliki data booking terkait.');
        }

        $jadwal->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal keberangkatan berhasil dihapus.');
    }

    /**
     * Toggle the status of the specified schedule (aktif <-> nonaktif).
     */
    public function toggleStatus(Jadwal $jadwal)
    {
        $newStatus = $jadwal->status_jadwal === 'aktif' ? 'nonaktif' : 'aktif';
        
        // If enabling it but it's already full, set status to penuh
        if ($newStatus === 'aktif') {
            $booked = $jadwal->bookings()
                ->whereNotIn('status_booking', ['cancelled', 'expired'])
                ->sum('jumlah_penumpang');
            
            if ($booked >= $jadwal->kuota) {
                $newStatus = 'penuh';
            }
        }

        $jadwal->update(['status_jadwal' => $newStatus]);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Status jadwal berhasil diubah menjadi ' . ucwords($newStatus) . '.');
    }
}
