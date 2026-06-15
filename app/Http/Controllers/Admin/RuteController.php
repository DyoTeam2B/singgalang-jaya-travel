<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRuteRequest;
use App\Http\Requests\Admin\UpdateRuteRequest;
use App\Models\Rute;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $rute = Rute::query()
            ->when($search, function ($query) use ($search) {
                $query->where('asal', 'like', "%{$search}%")
                      ->orWhere('tujuan', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rute.index', compact('rute', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRuteRequest $request)
    {
        Rute::create($request->validated());

        return redirect()
            ->route('admin.rute.index')
            ->with('success', 'Rute baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rute $rute)
    {
        return redirect()->route('admin.rute.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rute $rute)
    {
        return view('admin.rute.edit', compact('rute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRuteRequest $request, Rute $rute)
    {
        $rute->update($request->validated());

        return redirect()
            ->route('admin.rute.index')
            ->with('success', 'Data rute berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rute $rute)
    {
        // Safety check if route is associated with schedules
        if ($rute->jadwal()->exists()) {
            return redirect()
                ->route('admin.rute.index')
                ->with('error', 'Rute tidak dapat dihapus karena memiliki jadwal keberangkatan terkait.');
        }

        $rute->delete();

        return redirect()
            ->route('admin.rute.index')
            ->with('success', 'Rute berhasil dihapus.');
    }
}
