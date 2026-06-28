<!-- Sidebar Content -->
<div class="flex flex-col h-full overflow-hidden bg-[#0B1329] text-slate-300 font-poppins">
    <!-- Sidebar Header -->
    <div class="h-20 flex items-center px-6 border-b border-white/5 shrink-0 bg-[#070C1B]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5">
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-blue-500 rounded-xl flex items-center justify-center p-2 shadow-lg shadow-blue-500/20 ring-2 ring-white/10 shrink-0">
                <img src="{{ asset('logo1.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <div class="flex flex-col justify-center">
                <span class="font-extrabold text-white text-sm tracking-wide uppercase leading-tight">
                    Singgalang Jaya
                </span>
                <span class="font-semibold text-blue-400 text-[9px] uppercase tracking-[0.2em] leading-none opacity-90 mt-2">
                    Admin Control
                </span>
            </div>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 no-scrollbar">
        <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Navigasi Utama</p>
        
        <!-- Dashboard Link -->
        @php
            $dashboardActive = request()->routeIs('admin.dashboard');
            $dashboardUrl = route('admin.dashboard');
        @endphp
        <a href="{{ $dashboardUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $dashboardActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $dashboardActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Booking Link -->
        @php
            $bookingActive = request()->routeIs('admin.bookings.*');
            $bookingUrl = Route::has('admin.bookings.index') ? route('admin.bookings.index') : '#';
        @endphp
        <a href="{{ $bookingUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $bookingActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $bookingActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <span>Booking</span>
        </a>

        <!-- Pembayaran Link -->
        @php
            $paymentActive = request()->routeIs('admin.pembayaran.*');
            $paymentUrl = Route::has('admin.pembayaran.index') ? route('admin.pembayaran.index') : '#';
        @endphp
        <a href="{{ $paymentUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $paymentActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $paymentActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            <span>Pembayaran</span>
        </a>

        <!-- Rute Link -->
        @php
            $ruteActive = request()->routeIs('admin.rute.*');
            $ruteUrl = Route::has('admin.rute.index') ? route('admin.rute.index') : '#';
        @endphp
        <a href="{{ $ruteUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $ruteActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $ruteActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span>Rute</span>
        </a>

        <!-- Armada Link -->
        @php
            $armadaActive = request()->routeIs('admin.armada.*');
            $armadaUrl = Route::has('admin.armada.index') ? route('admin.armada.index') : '#';
        @endphp
        <a href="{{ $armadaUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $armadaActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $armadaActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
            </svg>
            <span>Armada</span>
        </a>

        <!-- Jadwal Link -->
        @php
            $jadwalActive = request()->routeIs('admin.jadwal.*');
            $jadwalUrl = Route::has('admin.jadwal.index') ? route('admin.jadwal.index') : '#';
        @endphp
        <a href="{{ $jadwalUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $jadwalActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $jadwalActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span>Jadwal</span>
        </a>

        <!-- Trip Link -->
        @php
            $tripActive = request()->routeIs('admin.trips.*');
            $tripUrl = Route::has('admin.trips.index') ? route('admin.trips.index') : '#';
        @endphp
        <a href="{{ $tripUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $tripActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $tripActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>Trip</span>
        </a>

        <!-- Driver Link -->
        @php
            $driverActive = request()->routeIs('admin.drivers.*');
            $driverUrl = Route::has('admin.drivers.index') ? route('admin.drivers.index') : '#';
        @endphp
        <a href="{{ $driverUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $driverActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $driverActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Driver</span>
        </a>

        <!-- Laporan Link -->
        @php
            $laporanActive = request()->routeIs('admin.laporan.*');
            $laporanUrl = Route::has('admin.laporan.index') ? route('admin.laporan.index') : '#';
        @endphp
        <a href="{{ $laporanUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $laporanActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $laporanActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Laporan</span>
        </a>

        <!-- Rating Link -->
        @php
            $ratingActive = request()->routeIs('admin.rating.*');
            $ratingUrl = Route::has('admin.rating.index') ? route('admin.rating.index') : '#';
        @endphp
        <a href="{{ $ratingUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $ratingActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $ratingActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.977-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <span>Manajemen Rating</span>
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-5 border-t border-white/5 bg-[#070C1B]">
        <div class="p-4 bg-white/[0.02] hover:bg-white/[0.04] rounded-2xl border border-white/5 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative flex h-2 w-2 animate-pulse">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none">Status Server</span>
                        <span class="text-[9px] font-semibold text-emerald-500 uppercase tracking-wider mt-1">Operational</span>
                    </div>
                </div>
                <span class="text-[9px] font-bold text-slate-400 bg-white/5 px-2 py-0.5 rounded-full uppercase tracking-wider">V1.3</span>
            </div>
        </div>
    </div>
</div>
