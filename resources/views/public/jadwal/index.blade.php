@extends('layouts.public')

@section('content')
    <section class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-12">
                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">Daftar Jadwal Keberangkatan</h1>
                <p class="text-slate-500 text-base md:text-lg font-medium">Temukan jadwal travel terbaik untuk rute perjalanan Anda.</p>
            </div>

            <!-- Search & Filter Card -->
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm p-6 md:p-8 mb-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/40 rounded-full blur-3xl pointer-events-none"></div>
                
                <form action="{{ route('jadwal.index') }}" method="GET" class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <!-- Origin -->
                    <div>
                        <label for="asal" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kota Asal</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="asal" 
                                id="asal" 
                                value="{{ request('asal') }}"
                                placeholder="Contoh: Padang Panjang" 
                                class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm font-semibold placeholder:text-slate-400"
                            >
                        </div>
                    </div>

                    <!-- Destination -->
                    <div>
                        <label for="tujuan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kota Tujuan</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="tujuan" 
                                id="tujuan" 
                                value="{{ request('tujuan') }}"
                                placeholder="Contoh: Pekanbaru" 
                                class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm font-semibold placeholder:text-slate-400"
                            >
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="tanggal" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Perjalanan</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                name="tanggal" 
                                id="tanggal" 
                                value="{{ request('tanggal') }}"
                                class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm font-semibold text-slate-700"
                            >
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 h-12 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/>
                            </svg>
                            Cari
                        </button>
                        @if(request()->anyFilled(['asal', 'tujuan', 'tanggal']))
                            <a href="{{ route('jadwal.index') }}" class="h-12 w-12 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-all flex items-center justify-center border border-slate-200" title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Schedules Result -->
            @if($schedules->isEmpty())
                <div class="bg-white rounded-3xl border border-slate-200/60 p-16 text-center shadow-sm max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><line x1="10" x2="14" y1="14" y2="18"/><line x1="14" x2="10" y1="14" y2="18"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Jadwal Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6">Maaf, jadwal dengan kriteria pencarian Anda belum tersedia saat ini.</p>
                    <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                        Tampilkan Semua Jadwal
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-8">
                    @foreach ($schedules as $schedule)
                        @php
                            $bookedSeats = (int) ($schedule->booked_seats ?? 0);
                            $sisaKursi = max(0, $schedule->kuota - $bookedSeats);
                            $fillPercentage = ($schedule->kuota > 0) ? (($schedule->kuota - $sisaKursi) / $schedule->kuota) * 100 : 0;
                            $isMorning = Str::lower($schedule->shift) === 'pagi';
                        @endphp
                        
                        <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-64 h-64 {{ $isMorning ? 'bg-blue-50/50' : 'bg-slate-100/60' }} rounded-full blur-3xl -z-0 pointer-events-none transition-colors"></div>

                            <div class="relative z-10 flex-grow flex flex-col">
                                <!-- Top: Route and Shift -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                                    <div class="flex items-center flex-wrap gap-2 md:gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $isMorning ? 'bg-blue-600 shadow-[0_0_0_4px_rgb(37,99,235,0.1)]' : 'bg-slate-900 shadow-[0_0_0_4px_rgb(15,23,42,0.1)]' }}"></span>
                                            <span class="text-slate-900 font-bold text-lg md:text-xl tracking-tight">{{ $schedule->rute->asal }}</span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-400 bg-white"></span>
                                            <span class="text-slate-900 font-bold text-lg md:text-xl tracking-tight">{{ $schedule->rute->tujuan }}</span>
                                        </div>
                                    </div>
                                    <div class="{{ $isMorning ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'bg-slate-900 text-white border-transparent' }} px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2 border w-fit">
                                        @if($isMorning)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="M4.93 4.93l1.41 1.41"/><path d="M17.66 17.66l1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="M6.34 17.66l-1.41 1.41"/><path d="M19.07 4.93l-1.41 1.41"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                                            </svg>
                                        @endif
                                        Shift {{ $schedule->shift }}
                                    </div>
                                </div>

                                <!-- Middle: Time & Date -->
                                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-6">
                                    <div>
                                        <p class="text-slate-500 text-xs font-semibold mb-1 uppercase tracking-wider">Jam Keberangkatan</p>
                                        <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tighter">
                                            {{ $schedule->jam_berangkat->format('H.i') }}
                                        </h3>
                                        <p class="text-slate-400 text-xs font-bold mt-1">
                                            {{ $schedule->tanggal_keberangkatan->format('d M Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="w-full sm:w-48 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-end mb-2">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Sisa Kursi</span>
                                            <span class="text-xl font-extrabold {{ $sisaKursi > 0 ? 'text-blue-600' : 'text-red-600' }} leading-none">{{ $sisaKursi }}</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full {{ $sisaKursi > 0 ? 'bg-blue-600' : 'bg-red-600' }} rounded-full" style="width: {{ $fillPercentage }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-semibold mt-1.5 text-right">Kapasitas: {{ $schedule->kuota }}</p>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="relative -mx-6 my-6">
                                    <div class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-r border-slate-200/60 z-20"></div>
                                    <div class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border-l border-slate-200/60 z-20"></div>
                                    <div class="border-t-2 border-dashed border-slate-200 w-full relative z-10"></div>
                                </div>

                                <!-- Vehicle & Price -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mt-auto">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M13 17H9"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-slate-900 font-bold text-sm">Toyota Avanza</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 text-xs font-medium">Maks. {{ $schedule->kuota }} Penumpang</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Harga per kursi</p>
                                        <p class="text-slate-900 font-extrabold text-xl tracking-tight">Rp {{ number_format($schedule->rute->tarif, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Button -->
                            @if($sisaKursi > 0)
                                <a href="{{ Route::has('booking.create') ? route('booking.create', ['jadwal_id' => $schedule->id]) : '#' }}" class="w-full h-12 mt-6 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-900/15 active:scale-[0.98] flex justify-center items-center relative z-10">
                                    Booking Sekarang
                                </a>
                            @else
                                <button disabled class="w-full h-12 mt-6 bg-slate-200 text-slate-400 rounded-xl font-bold cursor-not-allowed flex justify-center items-center relative z-10">
                                    Habis Terjual
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
