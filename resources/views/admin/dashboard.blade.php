@extends('layouts.admin')

@section('content')
    <div class="font-poppins space-y-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></span>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">Management Center</p>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Dashboard Admin</h1>
                <p class="text-sm font-semibold text-slate-500">Ringkasan aktivitas dan operasional travel Singgalang Jaya hari ini.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 bg-white border border-slate-200/80 px-4 py-2.5 rounded-xl shadow-sm">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Booking Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-blue-200/60 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:scale-110 transition-transform">
                        <!-- Ticket Icon SVG -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-950 tracking-tight mb-1">{{ number_format($totalBookings) }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Booking</p>
                </div>
            </div>

            <!-- Pending Verification Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-amber-200/60 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                        <!-- Clock Icon SVG -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @if($pendingVerification > 0)
                        <span class="text-[8px] font-black text-amber-600 bg-amber-50/60 px-2 py-1 rounded-lg border border-amber-100 uppercase tracking-wider">
                            Perlu Tindakan
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-950 tracking-tight mb-1">{{ number_format($pendingVerification) }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending Verifikasi</p>
                </div>
            </div>

            <!-- Active Trip Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-indigo-200/60 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-indigo-50 text-indigo-600 group-hover:scale-110 transition-transform">
                        <!-- Map Icon SVG -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                    <span class="text-[8px] font-black text-slate-500 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100 uppercase tracking-wider">
                        Berlangsung
                    </span>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-950 tracking-tight mb-1">{{ number_format($activeTrips) }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Trip Aktif</p>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-200/60 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-emerald-50 text-emerald-600 group-hover:scale-110 transition-transform">
                        <!-- Wallet Icon SVG -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-955 tracking-tight mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Bottom Layout: Table & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Bookings Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Booking Terbaru</h2>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Daftar pemesanan tiket terakhir</p>
                        </div>
                        <a href="{{ Route::has('admin.bookings.index') ? route('admin.bookings.index') : '#' }}" class="text-[10px] font-black text-blue-600 hover:text-blue-800 flex items-center gap-1 uppercase tracking-wider">
                            Lihat Semua 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">ID Booking</th>
                                    <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Nama Pelanggan</th>
                                    <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Jadwal / Rute</th>
                                    <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Shift</th>
                                    <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentBookings as $booking)
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="px-6 py-4 text-xs font-black text-slate-900 whitespace-nowrap">{{ $booking->kode_booking }}</td>
                                        <td class="px-6 py-4 text-xs font-semibold text-slate-700 whitespace-nowrap">{{ $booking->pelanggan->nama ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-xs font-bold text-slate-500 whitespace-nowrap">
                                            @if($booking->jadwal && $booking->jadwal->rute)
                                                {{ $booking->jadwal->rute->asal }} → {{ $booking->jadwal->rute->tujuan }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs font-black text-slate-500 whitespace-nowrap capitalize">
                                            {{ $booking->jadwal ? $booking->jadwal->shift : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-status-badge status="{{ $booking->status_booking }}" class="!text-[8px] !px-2" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-semibold text-xs">
                                            Belum ada booking terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Activity Panel -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">Aktivitas Terkini</h2>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Log sistem realtime</p>
                        </div>
                        <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-100">
                            <!-- Activity Icon SVG -->
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Log 1 -->
                        <div class="flex gap-4 relative">
                            <div class="absolute top-8 left-[11px] w-px h-full bg-slate-100 -bottom-6"></div>
                            <div class="relative z-10 w-6 h-6 rounded-full bg-blue-50 border-2 border-white flex items-center justify-center shrink-0 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-blue-600 rounded-full"></span>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-xs font-black text-slate-900 mb-0.5">Booking Baru Diterima</p>
                                <p class="text-[11px] font-semibold text-slate-500 leading-snug mb-1">Budi Santoso memesan 2 kursi (Shift Pagi)</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">10 menit yang lalu</p>
                            </div>
                        </div>

                        <!-- Log 2 -->
                        <div class="flex gap-4 relative">
                            <div class="absolute top-8 left-[11px] w-px h-full bg-slate-100 -bottom-6"></div>
                            <div class="relative z-10 w-6 h-6 rounded-full bg-emerald-50 border-2 border-white flex items-center justify-center shrink-0 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-xs font-black text-slate-900 mb-0.5">Pembayaran Diverifikasi</p>
                                <p class="text-[11px] font-semibold text-slate-500 leading-snug mb-1">Bukti transfer BKG-1028 telah diverifikasi oleh sistem</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">25 menit yang lalu</p>
                            </div>
                        </div>

                        <!-- Log 3 -->
                        <div class="flex gap-4 relative">
                            <div class="absolute top-8 left-[11px] w-px h-full bg-slate-100 -bottom-6"></div>
                            <div class="relative z-10 w-6 h-6 rounded-full bg-indigo-50 border-2 border-white flex items-center justify-center shrink-0 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-xs font-black text-slate-900 mb-0.5">Trip Telah Ditugaskan</p>
                                <p class="text-[11px] font-semibold text-slate-500 leading-snug mb-1">Driver Hendra ditugaskan untuk Trip #8821</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">1 jam yang lalu</p>
                            </div>
                        </div>

                        <!-- Log 4 -->
                        <div class="flex gap-4">
                            <div class="relative z-10 w-6 h-6 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center shrink-0 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                            </div>
                            <div class="pt-0.5">
                                <p class="text-xs font-black text-slate-900 mb-0.5">Trip Selesai</p>
                                <p class="text-[11px] font-semibold text-slate-500 leading-snug mb-1">Driver Ahmad (Shift Malam) tiba di tujuan</p>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">2 jam yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-8 py-3 text-xs font-black uppercase tracking-widest text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-xl transition-all border border-slate-100">
                    Lihat Semua Log
                </button>
            </div>
        </div>
    </div>
@endsection