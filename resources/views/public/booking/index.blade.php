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
            {{-- Mobile List (Card-based) --}}
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($bookings as $booking)
                    @php
                        $latestPayment = $booking->pembayaran->first();
                    @endphp
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $booking->kode_booking }}</p>
                                <p class="text-xs text-slate-400">{{ $booking->created_at->format('d M Y') }}</p>
                            </div>
                            <x-status-badge :status="$booking->status_booking" />
                        </div>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                                <span class="font-semibold">{{ $booking->jadwal->rute->asal ?? '-' }} → {{ $booking->jadwal->rute->tujuan ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                <span>
                                    {{ $booking->jadwal?->tanggal_keberangkatan?->format('d M Y') ?? '-' }}
                                    @if($booking->jadwal)
                                        - {{ ucfirst($booking->jadwal->shift) }} {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span>{{ $booking->jumlah_penumpang }} Orang</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                <span class="text-slate-400 font-medium">Pembayaran DP</span>
                                @if($latestPayment)
                                    <x-status-badge :status="$latestPayment->status_pembayaran" />
                                @else
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Belum Upload</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            @if($booking->status_booking === \App\Models\Booking::STATUS_BOOKING_DIBUAT)
                                <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-center text-xs font-bold transition-all active:scale-[0.98]">Upload DP</a>
                            @endif
                            <a href="{{ route('booking.show', ['kode' => $booking->kode_booking]) }}" class="flex-1 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-center text-xs font-bold transition-all active:scale-[0.98]">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl mx-auto flex items-center justify-center text-blue-600 mb-4 border border-blue-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-800">Belum Ada Booking</h2>
                        <p class="text-xs text-slate-500 mt-1">Mulai pesan perjalanan untuk melihat riwayat dan status di sini.</p>
                        <a href="{{ route('booking.create') }}" class="inline-flex items-center justify-center bg-blue-800 hover:bg-blue-900 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors text-xs mt-4">Booking Sekarang</a>
                    </div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
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