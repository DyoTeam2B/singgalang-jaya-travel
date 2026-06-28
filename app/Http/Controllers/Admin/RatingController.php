<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the ratings.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Rating::with(['booking.jadwal.rute', 'pelanggan']);

        if ($status && in_array($status, ['menunggu', 'published', 'hidden'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ulasan', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function ($pq) use ($search) {
                      $pq->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('booking', function ($bq) use ($search) {
                      $bq->where('kode_booking', 'like', "%{$search}%");
                  });
            });
        }

        $ratings = $query->latest()->paginate(10)->withQueryString();

        // Statistics
        $totalCount = Rating::count();
        $publishedCount = Rating::where('status', Rating::STATUS_PUBLISHED)->count();
        $pendingCount = Rating::where('status', Rating::STATUS_MENUNGGU)->count();
        $hiddenCount = Rating::where('status', Rating::STATUS_HIDDEN)->count();
        $averageRating = Rating::avg('rating') ?: 0;

        return view('admin.rating.index', compact(
            'ratings',
            'status',
            'search',
            'totalCount',
            'publishedCount',
            'pendingCount',
            'hiddenCount',
            'averageRating'
        ));
    }

    /**
     * Display the specified rating.
     */
    public function show($id)
    {
        $rating = Rating::with(['booking.jadwal.rute', 'pelanggan'])->findOrFail($id);
        return view('admin.rating.show', compact('rating'));
    }

    /**
     * Publish the specified rating.
     */
    public function publish($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->update(['status' => Rating::STATUS_PUBLISHED]);

        return redirect()
            ->back()
            ->with('success', 'Ulasan berhasil dipublikasikan di Landing Page.');
    }

    /**
     * Hide the specified rating.
     */
    public function hide($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->update(['status' => Rating::STATUS_HIDDEN]);

        return redirect()
            ->back()
            ->with('success', 'Ulasan berhasil disembunyikan.');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()
            ->route('admin.rating.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
