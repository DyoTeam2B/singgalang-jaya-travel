@props(['status'])

@php
    $status = strtolower($status);
    $config = match ($status) {
        // Booking Status
        'booking_dibuat' => ['label' => 'Booking Dibuat', 'classes' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'classes' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'dikonfirmasi' => ['label' => 'Dikonfirmasi', 'classes' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'assigned_to_trip' => ['label' => 'Assigned To Trip', 'classes' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'on_trip' => ['label' => 'On Trip', 'classes' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'completed' => ['label' => 'Completed', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-red-100 text-red-800 border-red-200'],
        'expired' => ['label' => 'Expired', 'classes' => 'bg-red-100 text-red-800 border-red-200'],

        // Payment Status
        'menunggu' => ['label' => 'Menunggu', 'classes' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
        'terverifikasi' => ['label' => 'Terverifikasi', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'ditolak' => ['label' => 'Ditolak', 'classes' => 'bg-red-100 text-red-800 border-red-200'],

        // Trip Status
        'new' => ['label' => 'New', 'classes' => 'bg-slate-100 text-slate-600 border-slate-200'],
        'ready' => ['label' => 'Ready', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'on_trip' => ['label' => 'On Trip', 'classes' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'completed' => ['label' => 'Completed', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-red-100 text-red-800 border-red-200'],

        // Driver & Schedule Status
        'aktif' => ['label' => 'Aktif', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'nonaktif' => ['label' => 'Nonaktif', 'classes' => 'bg-red-100 text-red-800 border-red-200'],
        'penuh' => ['label' => 'Penuh', 'classes' => 'bg-slate-100 text-slate-800 border-slate-200'],
        'tersedia' => ['label' => 'Tersedia', 'classes' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'sedang_bertugas' => ['label' => 'Sedang Bertugas', 'classes' => 'bg-blue-50 text-blue-700 border-blue-200'],
        'tidak_aktif' => ['label' => 'Tidak Aktif', 'classes' => 'bg-slate-100 text-slate-600 border-slate-200'],

        // Pickup / Dropoff Status
        'belum' => ['label' => 'Belum', 'classes' => 'bg-slate-100 text-slate-600 border-slate-200'],
        'sudah_dijemput' => ['label' => 'Sudah Dijemput', 'classes' => 'bg-green-100 text-green-800 border-green-200'],
        'sudah_diantar' => ['label' => 'Sudah Diantar', 'classes' => 'bg-green-100 text-green-800 border-green-200'],

        // Default fallback
        default => ['label' => ucwords(str_replace('_', ' ', $status)), 'classes' => 'bg-slate-100 text-slate-600 border-slate-200'],
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ' . $config['classes']]) }}>
    {{ $config['label'] }}
</span>
