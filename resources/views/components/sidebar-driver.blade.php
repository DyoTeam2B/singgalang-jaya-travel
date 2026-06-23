<!-- Sidebar Content -->
<div class="flex flex-col h-full overflow-hidden bg-[#0B1329] text-slate-300 font-poppins">
    <!-- Sidebar Header -->
    <div class="h-20 flex items-center px-6 border-b border-white/5 shrink-0 bg-[#070C1B]">
        <a href="{{ route('driver.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-blue-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 ring-2 ring-white/10 shrink-0">
                <!-- Map icon SVG -->
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="font-extrabold text-white text-sm tracking-wide uppercase">
                    Singgalang Jaya
                </span>
                <span class="font-semibold text-blue-400 text-[9px] uppercase tracking-[0.2em] leading-none opacity-90 mt-0.5">
                    Driver Portal
                </span>
            </div>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5 no-scrollbar">
        <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Navigasi Driver</p>
        
        <!-- Dashboard Link -->
        @php
            $dashboardActive = request()->routeIs('driver.dashboard');
            $dashboardUrl = route('driver.dashboard');
        @endphp
        <a href="{{ $dashboardUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $dashboardActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $dashboardActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Trip History Link -->
        @php
            $tripsActive = request()->routeIs('driver.trips.*');
            $tripsUrl = Route::has('driver.trips.index') ? route('driver.trips.index') : '#';
        @endphp
        <a href="{{ $tripsUrl }}" class="flex items-center gap-3.5 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all duration-300 group {{ $tripsActive ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:bg-white/[0.04] hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-105 {{ $tripsActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Riwayat Trip</span>
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
            </div>
        </div>
    </div>
</div>
