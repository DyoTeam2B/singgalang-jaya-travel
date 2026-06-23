@extends('layouts.driver')

@section('content')
    <div class="space-y-8 font-poppins w-full max-w-full min-w-0">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-1 uppercase">Riwayat Perjalanan</h1>
                <p class="text-xs font-bold text-slate-400">Kelola dan pantau seluruh perjalanan yang ditugaskan kepada Anda.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('driver.dashboard') }}" class="flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.98] shadow-xl shadow-blue-600/10">
                    Panel Aktif
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                        <!-- Car Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Trip Selesai</p>
                </div>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $stats['total_trips'] }} Perjalanan</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                        <!-- Users Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Penumpang</p>
                </div>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $stats['total_passengers'] }} Orang</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                <div>
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                        <!-- Wallet Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Pendapatan Tunai</p>
                </div>
                <h3 class="text-lg font-black text-emerald-600 mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Filter & List Area -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden w-full">
            <div class="overflow-x-auto no-scrollbar w-full max-w-full min-w-0">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Detail Trip</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Jadwal</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Okupansi</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Pendapatan Cash</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($trips as $trip)
                            @php
                                $revenue = $trip->detailTrips->sum(fn($dt) => $dt->booking ? max(0, $dt->booking->total_harga - 50000) : 0);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                            <!-- Car SVG -->
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-slate-900 leading-none mb-1 uppercase truncate">{{ $trip->jadwal->rute->asal }} &rarr; {{ $trip->jadwal->rute->tujuan }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter truncate">TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }} • {{ $trip->armada->nomor_plat ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    <p class="leading-none mb-1">{{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}</p>
                                    <p class="text-[9px] font-black text-blue-600 uppercase tracking-wider">Shift {{ ucfirst($trip->jadwal->shift) }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-600">
                                    <div class="flex items-center gap-1.5">
                                        <!-- Users SVG -->
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span>{{ $trip->detailTrips->count() }} PAX</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-black text-emerald-600">
                                    Rp {{ number_format($revenue, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-status-badge :status="$trip->status_trip" class="!text-[8px] !px-2.5 !py-0.5" />
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('driver.trips.show', $trip->id) }}" class="p-2 inline-flex items-center justify-center bg-slate-100 group-hover:bg-blue-50 group-hover:text-blue-600 text-slate-500 rounded-xl transition-all">
                                        <!-- Eye icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-bold italic">
                                    Belum ada catatan perjalanan yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($trips->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $trips->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
