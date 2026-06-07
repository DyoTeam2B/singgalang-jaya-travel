<!-- Sidebar Content -->
<div class="flex flex-col h-full overflow-hidden">
    <!-- Sidebar Header -->
    <div class="h-24 flex items-center px-8 border-b border-white/5 shrink-0 bg-slate-950/20">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                <!-- Map icon SVG -->
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-black text-white text-base leading-none tracking-tight mb-1 uppercase">
                    Singgalang Jaya
                </span>
                <span class="font-bold text-blue-400 text-[10px] uppercase tracking-[0.2em] leading-none opacity-80">
                    Admin Control
                </span>
            </div>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto py-8 px-6 space-y-2 no-scrollbar">
        <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-6">Menu Navigasi</p>
        
        <!-- Dashboard Link -->
        @php
            $dashboardActive = request()->routeIs('admin.dashboard');
            $dashboardUrl = route('admin.dashboard');
        @endphp
        <a href="{{ $dashboardUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $dashboardActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $dashboardActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
            </svg>
            Dashboard
        </a>

        <!-- Booking Link -->
        @php
            $bookingActive = request()->routeIs('admin.bookings.*');
            $bookingUrl = Route::has('admin.bookings.index') ? route('admin.bookings.index') : '#';
        @endphp
        <a href="{{ $bookingUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $bookingActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $bookingActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            Booking
        </a>

        <!-- Pembayaran Link -->
        @php
            $paymentActive = request()->routeIs('admin.pembayaran.*');
            $paymentUrl = Route::has('admin.pembayaran.index') ? route('admin.pembayaran.index') : '#';
        @endphp
        <a href="{{ $paymentUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $paymentActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $paymentActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pembayaran
        </a>

        <!-- Jadwal Link -->
        @php
            $jadwalActive = request()->routeIs('admin.jadwal.*');
            $jadwalUrl = Route::has('admin.jadwal.index') ? route('admin.jadwal.index') : '#';
        @endphp
        <a href="{{ $jadwalUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $jadwalActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $jadwalActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Jadwal
        </a>

        <!-- Trip Link -->
        @php
            $tripActive = request()->routeIs('admin.trips.*');
            $tripUrl = Route::has('admin.trips.index') ? route('admin.trips.index') : '#';
        @endphp
        <a href="{{ $tripUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $tripActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $tripActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Trip
        </a>

        <!-- Driver Link -->
        @php
            $driverActive = request()->routeIs('admin.drivers.*');
            $driverUrl = Route::has('admin.drivers.index') ? route('admin.drivers.index') : '#';
        @endphp
        <a href="{{ $driverUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $driverActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $driverActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Driver
        </a>

        <!-- Laporan Link -->
        @php
            $laporanActive = request()->routeIs('admin.laporan.*');
            $laporanUrl = Route::has('admin.laporan.index') ? route('admin.laporan.index') : '#';
        @endphp
        <a href="{{ $laporanUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $laporanActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $laporanActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Laporan
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-8 border-t border-white/5 bg-slate-950/20">
        <div class="p-5 bg-white/5 rounded-3xl border border-white/5">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Status Server</p>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-bold text-emerald-500 uppercase">Operational</span>
            </div>
        </div>
    </div>
</div>
