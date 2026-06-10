<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = Driver::query()->with(['user', 'trips']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_driver', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('nomor_plat', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter mapping
        if ($statusFilter) {
            if ($statusFilter === 'Tersedia') {
                $query->where('status_driver', 'aktif')
                      ->whereDoesntHave('trips', function ($tq) {
                          $tq->whereIn('status_trip', ['ready', 'berjalan']);
                      });
            } elseif ($statusFilter === 'Sedang Bertugas') {
                $query->where('status_driver', 'aktif')
                      ->whereHas('trips', function ($tq) {
                          $tq->whereIn('status_trip', ['ready', 'berjalan']);
                      });
            } elseif ($statusFilter === 'Tidak Aktif') {
                $query->where('status_driver', 'nonaktif');
            }
        }

        $drivers = $query->latest()->paginate(10)->withQueryString();

        // Handle active/selected driver for the details panel
        $selectedDriverId = $request->input('selected_id');
        $selectedDriver = null;

        if ($selectedDriverId) {
            $selectedDriver = Driver::with(['user', 'trips.jadwal.rute'])->find($selectedDriverId);
        }

        if (!$selectedDriver && $drivers->count() > 0) {
            $selectedDriver = Driver::with(['user', 'trips.jadwal.rute'])->find($drivers->first()->id);
        }

        return view('admin.drivers.index', compact('drivers', 'search', 'statusFilter', 'selectedDriver'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // Create user login account for the driver
                $user = User::create([
                    'name' => $request->nama_driver,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'driver',
                ]);

                // Create the driver data
                Driver::create([
                    'user_id' => $user->id,
                    'nama_driver' => $request->nama_driver,
                    'no_hp' => $request->no_hp,
                    'nama_mobil' => $request->nama_mobil,
                    'nomor_plat' => $request->nomor_plat,
                    'kapasitas_mobil' => $request->kapasitas_mobil,
                    'status_driver' => $request->status_driver,
                ]);
            });

            return redirect()
                ->route('admin.drivers.index')
                ->with('success', 'Driver baru dan akun login berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data driver: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        try {
            DB::transaction(function () use ($request, $driver) {
                // Update corresponding User account
                $userData = [
                    'name' => $request->nama_driver,
                    'email' => $request->email,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $driver->user->update($userData);

                // Update Driver record
                $driver->update([
                    'nama_driver' => $request->nama_driver,
                    'no_hp' => $request->no_hp,
                    'nama_mobil' => $request->nama_mobil,
                    'nomor_plat' => $request->nomor_plat,
                    'kapasitas_mobil' => $request->kapasitas_mobil,
                    'status_driver' => $request->status_driver,
                ]);
            });

            return redirect()
                ->route('admin.drivers.index', ['selected_id' => $driver->id])
                ->with('success', 'Data driver berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data driver: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        // Check if driver is currently assigned to active trips
        $hasActiveTrip = $driver->trips()
            ->whereIn('status_trip', ['ready', 'berjalan'])
            ->exists();

        if ($hasActiveTrip) {
            return redirect()
                ->route('admin.drivers.index')
                ->with('error', 'Driver tidak dapat dihapus karena sedang bertugas dalam trip aktif.');
        }

        try {
            DB::transaction(function () use ($driver) {
                // Deleting the user cascades and deletes the driver
                $driver->user->delete();
            });

            return redirect()
                ->route('admin.drivers.index')
                ->with('success', 'Data driver beserta akun login berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.drivers.index')
                ->with('error', 'Gagal menghapus driver: ' . $e->getMessage());
        }
    }
}
