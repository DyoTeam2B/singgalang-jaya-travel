<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.pembayaran.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Redirect to index page with the selected payment query parameter for unified split-screen experience
        return redirect()->route('admin.pembayaran.index', ['payment_id' => $id]);
    }

    /**
     * Verify the specified payment.
     */
    public function verify(Pembayaran $pembayaran)
    {
        DB::transaction(function () use ($pembayaran) {
            $pembayaran->update([
                'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
            ]);

            $pembayaran->booking->update([
                'status_booking' => Booking::STATUS_DIKONFIRMASI,
            ]);
        });

        return redirect()
            ->route('admin.pembayaran.index', ['payment_id' => $pembayaran->id])
            ->with('success', 'Pembayaran berhasil diverifikasi. Status booking diperbarui menjadi Dikonfirmasi.');
    }

    /**
     * Reject the specified payment.
     */
    public function reject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        DB::transaction(function () use ($pembayaran, $request) {
            $pembayaran->update([
                'status_pembayaran' => Pembayaran::STATUS_DITOLAK,
                'catatan' => $request->catatan,
            ]);

            $pembayaran->booking->update([
                'status_booking' => Booking::STATUS_MENUNGGU_PEMBAYARAN,
            ]);
        });

        return redirect()
            ->route('admin.pembayaran.index', ['payment_id' => $pembayaran->id])
            ->with('error', 'Pembayaran ditolak. Pelanggan diminta mengunggah ulang bukti pembayaran.');
    }
}
