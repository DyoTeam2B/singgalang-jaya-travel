<!-- Sidebar Content -->
<div class="flex flex-col h-full overflow-hidden">
    <!-- Sidebar Header -->
    <div class="h-24 flex items-center px-8 border-b border-white/5 shrink-0 bg-slate-950/20">
        <a href="{{ route('driver.dashboard') }}" class="flex items-center gap-4">
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
                    Driver Portal
                </span>
            </div>
        </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="flex-1 overflow-y-auto py-8 px-6 space-y-2 no-scrollbar">
        <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-6">Menu Navigasi</p>
        
        <!-- Dashboard Link -->
        @php
            $dashboardActive = request()->routeIs('driver.dashboard');
            $dashboardUrl = route('driver.dashboard');
        @endphp
        <a href="{{ $dashboardUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $dashboardActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $dashboardActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
            </svg>
            Dashboard
        </a>

        <!-- Trip History Link -->
        @php
            $tripsActive = request()->routeIs('driver.trips.*');
            $tripsUrl = Route::has('driver.trips.index') ? route('driver.trips.index') : '#';
        @endphp
        <a href="{{ $tripsUrl }}" class="flex items-center gap-4 px-5 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all duration-300 group {{ $tripsActive ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 {{ $tripsActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Riwayat Trip
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
