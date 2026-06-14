<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArmadaRequest;
use App\Http\Requests\Admin\UpdateArmadaRequest;
use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = Armada::query()->with(['driver', 'trips']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mobil', 'like', "%{$search}%")
                  ->orWhere('nomor_plat', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('status_armada', $statusFilter);
        }

        $armadas = $query->latest()->paginate(10)->withQueryString();

        $selectedArmadaId = $request->input('selected_id');
        $selectedArmada = null;

        if ($selectedArmadaId) {
            $selectedArmada = Armada::with(['driver', 'trips.jadwal.rute'])->find($selectedArmadaId);
        }

        if (!$selectedArmada && $armadas->count() > 0) {
            $selectedArmada = Armada::with(['driver', 'trips.jadwal.rute'])->find($armadas->first()->id);
        }

        return view('admin.armada.index', compact('armadas', 'search', 'statusFilter', 'selectedArmada'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArmadaRequest $request)
    {
        $armada = Armada::create($request->validated());

        return redirect()
            ->route('admin.armada.index', ['selected_id' => $armada->id])
            ->with('success', 'Armada baru berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArmadaRequest $request, Armada $armada)
    {
        $armada->update($request->validated());

        return redirect()
            ->route('admin.armada.index', ['selected_id' => $armada->id])
            ->with('success', 'Data armada berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Armada $armada)
    {
        // Check if armada has driver
        if ($armada->driver()->exists()) {
            return redirect()
                ->route('admin.armada.index', ['selected_id' => $armada->id])
                ->with('error', 'Armada tidak dapat dihapus karena masih digunakan oleh driver.');
        }

        // Check if armada is assigned to trips
        if ($armada->trips()->whereIn('status_trip', ['ready', 'on_trip'])->exists()) {
            return redirect()
                ->route('admin.armada.index', ['selected_id' => $armada->id])
                ->with('error', 'Armada tidak dapat dihapus karena sedang bertugas dalam trip aktif.');
        }

        $armada->delete();

        return redirect()
            ->route('admin.armada.index')
            ->with('success', 'Armada berhasil dihapus.');
    }
}
