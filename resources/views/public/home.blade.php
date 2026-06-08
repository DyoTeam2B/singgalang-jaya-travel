@extends('layouts.public')

@section('content')
    <!-- HERO SECTION -->
    <section id="home" class="relative pt-16 pb-24 md:pt-24 md:pb-32 bg-slate-50 overflow-hidden">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"></div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-8 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-6 flex flex-col gap-8">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 leading-[1.1] tracking-tight">
                        Travel <br />
                        <span class="text-blue-600">Padang Panjang</span>
                        <span class="flex items-center gap-3 mt-2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 md:w-12 md:h-12 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                            <span class="text-slate-900">Pekanbaru</span>
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-500 max-w-lg leading-relaxed font-medium">
                        Nikmati perjalanan nyaman, aman, dan tepat waktu dengan sistem booking tiket online langsung dari perangkat Anda.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mt-2">
                        <a href="{{ Route::has('booking.create') ? route('booking.create') : '#' }}" class="bg-blue-800 hover:bg-blue-900 text-white px-8 py-4 rounded-full font-semibold text-lg transition-all shadow-lg shadow-blue-800/25 flex items-center justify-center gap-2 group w-full sm:w-auto">
                            Booking Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </a>
                        <a href="#jadwal" class="bg-white hover:bg-slate-50 text-slate-700 px-8 py-4 rounded-full font-semibold text-lg transition-all shadow-sm border border-slate-200 flex items-center justify-center gap-2 group w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                            Lihat Jadwal
                        </a>
                    </div>
                </div>

                <!-- Right Content / Graphics -->
                <div class="lg:col-span-6 relative z-0">
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-900/10 border-8 border-white bg-white">
                        <img 
                            src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
                            alt="Toyota Avanza Travel"
                            class="w-full h-auto object-cover aspect-[4/3] transform hover:scale-105 transition-transform duration-700"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/30 to-transparent pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="fitur" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-64 bg-blue-50/40 rounded-full blur-[100px] -z-0 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-4xl tracking-tight mb-4">
                    Kenapa Memilih Singgalang Jaya Travel?
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Layanan travel nyaman dan mudah untuk perjalanan Anda
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-[2rem] border border-slate-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -inset-px bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-xl opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-700 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m12 14 4-4 4 4"/><path d="M16 20V10"/><circle cx="8" cy="12" r="4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            Online Booking
                        </h3>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed">
                            Pemesanan travel langsung melalui sistem website yang cepat, aman, dan mudah.
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-[2rem] border border-slate-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -inset-px bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-xl opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-700 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            Jadwal Teratur
                        </h3>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed">
                            Keberangkatan shift pagi dan malam tersedia setiap hari tanpa khawatir delay.
                        </p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-[2rem] border border-slate-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -inset-px bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-xl opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-700 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            Door to Door
                        </h3>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed">
                            Penjemputan dan pengantaran langsung sesuai dengan titik lokasi Anda.
                        </p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-[2rem] border border-slate-200/50 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -inset-px bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none"></div>
                    <div class="relative mb-8">
                        <div class="absolute inset-0 bg-blue-100 rounded-2xl blur-xl opacity-0 group-hover:opacity-60 transition-opacity duration-300"></div>
                        <div class="relative w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors duration-300 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-700 group-hover:text-white transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m22 10-6 6-2-2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            Driver Profesional
                        </h3>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed">
                            Driver berpengalaman, ramah, dan tersertifikasi untuk kenyamanan perjalanan Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SCHEDULES SECTION -->
    <section id="jadwal" class="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
                <div class="max-w-2xl">
                    <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
                        Jadwal Keberangkatan Hari Ini
                    </h2>
                    <p class="text-slate-500 text-lg md:text-xl font-medium">
                        Pilih jadwal perjalanan yang tersedia
                    </p>
                </div>
                <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                    Lihat Semua Jadwal
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
            </div>

            @if($schedules->isEmpty())
                <div class="bg-white rounded-[2rem] border border-slate-200/60 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Jadwal Aktif</h3>
                    <p class="text-slate-500 text-sm font-medium">Tidak ada jadwal keberangkatan untuk hari ini.</p>
                </div>
            @else
                <div class="grid lg:grid-cols-2 gap-10">
                    @foreach ($schedules as $schedule)
                        @php
                            $bookedSeats = (int) ($schedule->booked_seats ?? 0);
                            $sisaKursi = max(0, $schedule->kuota - $bookedSeats);
                            $fillPercentage = ($schedule->kuota > 0) ? (($schedule->kuota - $sisaKursi) / $schedule->kuota) * 100 : 0;
                            $isMorning = Str::lower($schedule->shift) === 'pagi';
                        @endphp
                        
                        <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group flex flex-col p-6">
                            <div class="absolute top-0 right-0 w-64 h-64 {{ $isMorning ? 'bg-blue-50/50' : 'bg-slate-100/60' }} rounded-full blur-3xl -z-0 pointer-events-none transition-colors"></div>

                            <div class="relative z-10 flex-1 flex flex-col">
                                <!-- Route & Badge -->
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

                                <!-- Time & Seats -->
                                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-4">
                                    <div>
                                        <p class="text-slate-500 text-sm font-semibold mb-1 uppercase tracking-wider">Keberangkatan</p>
                                        <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tighter">
                                            {{ $schedule->jam_berangkat->format('H.i') }}
                                        </h3>
                                        <p class="text-slate-400 text-xs font-medium mt-1">
                                            Tanggal: {{ $schedule->tanggal_keberangkatan->format('d M Y') }}
                                        </p>
                                    </div>
                                    
                                    <div class="w-full sm:w-48 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-end mb-2">
                                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Sisa Kursi</span>
                                            <span class="text-xl font-extrabold {{ $sisaKursi > 0 ? 'text-blue-600' : 'text-red-600' }} leading-none">{{ $sisaKursi }}</span>
                                        </div>
                                        <!-- Progress Bar -->
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

                                <!-- Vehicle & Price Area -->
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
                                            <p class="text-slate-500 text-xs font-medium">Maks. 5 Penumpang</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Harga per kursi</p>
                                        <p class="text-slate-900 font-extrabold text-xl tracking-tight">Rp {{ number_format($schedule->rute->tarif, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
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

    <!-- FLEET SECTION (ARMADA) -->
    <section id="armada" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="mb-16 max-w-2xl">
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
                    Armada Travel
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Kendaraan nyaman untuk perjalanan Anda
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach (range(1, 4) as $idx)
                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 p-3 group flex flex-col">
                        <!-- Image & Floating Badge -->
                        <div class="relative h-48 w-full rounded-[1.5rem] overflow-hidden mb-5 bg-slate-100">
                            <img 
                                src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
                                alt="Toyota Avanza"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                            <div class="absolute top-3 right-3 bg-white/80 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-white/50">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-extrabold text-slate-800 uppercase tracking-wider">Tersedia</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="px-3 flex-grow flex flex-col">
                            <h4 class="text-xl font-extrabold text-slate-900 tracking-tight mb-1.5">Toyota Avanza</h4>
                            <p class="text-slate-500 text-sm font-medium mb-5">Travel premium, AC dingin, & bersih.</p>
                            
                            <div class="flex items-center gap-2.5 text-slate-700 bg-slate-50/80 w-fit px-3 py-2 rounded-xl border border-slate-100 mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                <span class="text-sm font-bold">Maksimal 5 Penumpang</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="mx-3 border-t border-dashed border-slate-200 mb-5"></div>

                        <div class="px-3 pb-3">
                            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Rute Operasional</p>
                            <p class="text-slate-900 font-bold text-sm">Padang Panjang ↔ Pekanbaru</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CHARTER SECTION -->
    <section id="charter" class="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    
                    <!-- Left Content -->
                    <div class="flex-1 p-10 md:p-16 lg:p-20 flex flex-col justify-center">
                        <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider w-fit mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M13 17H9"/>
                            </svg>
                            Layanan Privat
                        </div>
                        
                        <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-6">
                            Sewa Mobil Charter
                        </h2>
                        <p class="text-slate-500 text-lg md:text-xl font-medium mb-8 leading-relaxed">
                            Tersedia layanan charter mobil untuk kebutuhan perjalanan pribadi dan rombongan.
                        </p>

                        <div class="bg-slate-50 rounded-2xl p-5 flex items-start gap-4 border border-slate-100 mb-10">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm border border-slate-200/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-semibold leading-snug pt-2 text-sm">
                                Pemesanan charter dilakukan langsung melalui admin WhatsApp.
                            </p>
                        </div>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20layanan%20charter%20Singgalang%20Jaya." 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="bg-[#25D366] hover:bg-[#128C7E] text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-[#25D366]/20 active:scale-[0.98] w-fit flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                            </svg>
                            Hubungi Admin via WA
                        </a>
                    </div>

                    <!-- Right Image -->
                    <div class="flex-1 bg-slate-100 relative min-h-[350px] lg:min-h-full">
                        <img 
                            src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
                            alt="Charter Toyota Avanza"
                            class="absolute inset-0 w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/10 to-transparent pointer-events-none"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="kontak" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-16 max-w-2xl text-center mx-auto">
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4">
                    Hubungi Kami
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Kami siap membantu perjalanan Anda 24/7
                </p>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                <!-- Cards -->
                <div class="lg:col-span-7 grid sm:grid-cols-2 gap-6">
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="bg-white rounded-[2rem] border border-slate-200/60 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(16,185,129,0.1)] hover:-translate-y-1 transition-all duration-300 group flex flex-col items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">WhatsApp Admin</p>
                            <p class="text-lg font-bold text-slate-900">+62 812-3456-7890</p>
                        </div>
                        <div class="mt-auto pt-4 flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-slate-900 transition-colors">
                            Hubungi Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                    </a>

                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="bg-white rounded-[2rem] border border-slate-200/60 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(236,72,153,0.1)] hover:-translate-y-1 transition-all duration-300 group flex flex-col items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-pink-50 text-pink-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Instagram</p>
                            <p class="text-lg font-bold text-slate-900">@singgalangjayatravel</p>
                        </div>
                        <div class="mt-auto pt-4 flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-slate-900 transition-colors">
                            Hubungi Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                    </a>

                    <a href="mailto:hello@singgalangjaya.com" class="bg-white rounded-[2rem] border border-slate-200/60 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(37,99,235,0.1)] hover:-translate-y-1 transition-all duration-300 group flex flex-col items-start gap-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500 mb-1">Email</p>
                            <p class="text-lg font-bold text-slate-900">hello@singgalangjaya.com</p>
                        </div>
                        <div class="mt-auto pt-4 flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-slate-900 transition-colors">
                            Kirim Email
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- Address -->
                <div class="lg:col-span-5 bg-slate-900 rounded-[2rem] p-10 lg:p-12 flex flex-col justify-between text-white relative overflow-hidden group shadow-[0_20px_40px_rgb(15,23,42,0.2)]">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -z-0 pointer-events-none group-hover:bg-blue-500/20 transition-colors"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        
                        <div>
                            <h3 class="text-2xl font-extrabold mb-3 tracking-tight">Kantor Pusat</h3>
                            <p class="text-slate-300 text-lg font-medium leading-relaxed">
                                Padang Panjang, Sumatera Barat
                            </p>
                        </div>
                    </div>

                    <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                        <p class="text-slate-400 text-sm font-bold tracking-wide uppercase">Buka Setiap Hari: 06:00 - 22:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BANNER SECTION -->
    <section class="py-20 relative bg-slate-900 text-white overflow-hidden">
        <div class="absolute inset-0 bg-blue-800/10 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center flex flex-col items-center gap-6">
            <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight max-w-2xl leading-tight">
                Siap Melakukan Perjalanan Bersama Kami?
            </h2>
            <p class="text-slate-300 text-lg max-w-lg font-medium mb-4">
                Pesan tiket perjalanan Anda sekarang juga dengan cepat dan nikmati kenyamanan travel door-to-door.
            </p>
            <a href="{{ Route::has('booking.create') ? route('booking.create') : '#' }}" class="bg-white hover:bg-slate-100 text-slate-900 px-8 py-4 rounded-full font-bold text-lg transition-all shadow-xl shadow-white/5 flex items-center gap-2 group">
                Booking Tiket Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </div>
    </section>
@endsection
