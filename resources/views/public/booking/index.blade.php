@extends('layouts.public')

@section('title', 'Booking Saya - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Pelanggan</p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight">Booking Saya</h1>
                <p class="text-sm text-slate-500 mt-2">Pantau status pembayaran, verifikasi, dan penugasan trip Anda.</p>
            </div>
            <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm shadow-sm">
                Booking Baru
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>

        <x-alert />

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rute & Jadwal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Penumpang</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            @php
                                $latestPayment = $booking->pembayaran->first();
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-bold text-slate-900">{{ $booking->kode_booking }}</p>
                                    <p class="text-xs text-slate-400">{{ $booking->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-6 py-4 min-w-[220px]">
                                    <p class="text-sm font-semibold text-slate-800">{{ $booking->jadwal->rute->asal ?? '-' }} -> {{ $booking->jadwal->rute->tujuan ?? '-' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $booking->jadwal?->tanggal_keberangkatan?->format('d M Y') ?? '-' }}
                                        @if($booking->jadwal)
                                            - {{ ucfirst($booking->jadwal->shift) }} {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB
                                        @endif
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $booking->jumlah_penumpang }} orang</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($latestPayment)
                                        <x-status-badge :status="$latestPayment->status_pembayaran" />
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">Belum Upload</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$booking->status_booking" />
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        @if($booking->status_booking === \App\Models\Booking::STATUS_BOOKING_DIBUAT)
                                            <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold transition-colors">Upload DP</a>
                                        @endif
                                        <a href="{{ route('booking.show', ['kode' => $booking->kode_booking]) }}" class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-colors">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="max-w-sm mx-auto space-y-4">
                                        <div class="w-16 h-16 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center text-blue-600">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-lg font-bold text-slate-800">Belum Ada Booking</h2>
                                            <p class="text-sm text-slate-500 mt-1">Mulai pesan perjalanan untuk melihat riwayat dan status di sini.</p>
                                        </div>
                                        <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">Booking Sekarang</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection