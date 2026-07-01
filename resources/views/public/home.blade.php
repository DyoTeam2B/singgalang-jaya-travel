@extends('layouts.public')

@section('content')
    <!-- HERO SECTION -->
    <section id="home" class="relative pt-16 pb-24 md:pt-24 md:pb-32 bg-slate-50 overflow-hidden">
        <!-- GlowBackground Variant: Hero -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[-12%] right-[-6%] w-[620px] h-[620px] rounded-full blur-[130px] opacity-[0.18] bg-[#4F46E5]"></div>
            <div class="absolute top-[28%] left-[-12%] w-[460px] h-[460px] rounded-full blur-[110px] opacity-[0.12] bg-[#2563EB]"></div>
            <div class="absolute bottom-[-8%] right-[12%] w-[320px] h-[320px] rounded-full blur-[90px] opacity-[0.20] bg-[#0EA5E9]"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-10 items-center">
                
                <!-- Left Content -->
                <div class="lg:col-span-6 flex flex-col gap-7">
                    <span class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-blue-100 text-blue-700 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-[0.12em] w-fit shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                        </span>
                        Travel Door-to-Door Terpercaya
                    </span>

                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-slate-900 leading-[1.05] tracking-tight">
                        Perjalanan <br />
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Padang Panjang</span>
                        <span class="flex items-center gap-3 mt-1 text-slate-900">
                            ke Pekanbaru
                        </span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-slate-500 max-w-lg leading-relaxed font-medium">
                        Nikmati perjalanan nyaman, aman, dan tepat waktu dengan penjemputan langsung dari depan rumah Anda. Booking online dalam hitungan menit.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mt-1">
                        <a href="{{ Route::has('booking.create') ? route('booking.create') : '#' }}" 
                           class="bg-blue-800 hover:bg-blue-900 text-white px-8 h-14 rounded-xl font-bold text-base transition-all shadow-lg shadow-blue-800/20 flex items-center justify-center gap-2 group w-full sm:w-auto active:scale-[0.98]">
                            Booking Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                            </svg>
                        </a>
                        <a href="#jadwal" 
                           class="bg-white hover:bg-slate-50 text-slate-700 px-8 h-14 rounded-xl font-bold text-base transition-all shadow-sm border border-slate-200 flex items-center justify-center gap-2 group w-full sm:w-auto active:scale-[0.98]">
                            <!-- Calendar icon -->
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zm2.25-2.25h.008v.008H16.5V15zm0 2.25h.008v.008H16.5v-.008z"></path>
                            </svg>
                            Lihat Jadwal
                        </a>
                    </div>

                    <!-- Trust Stats -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 mt-4 pt-6 border-t border-slate-200/70">
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">12K+</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Penumpang</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 flex items-center gap-1.5 tracking-tight">
                                4.9
                                <!-- Star Icon -->
                                <svg class="w-4 h-4 fill-amber-400 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Rating</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 tracking-tight">2 Jam</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Estimasi Keberangkatan</p>
                        </div>
                    </div>
                </div>

                <!-- Right Content / Graphics -->
                <div class="lg:col-span-6 relative z-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-blue-900/10 border-8 border-white bg-white">
                        <img 
                            src="{{ asset('travel_avanza_hero.png') }}" 
                            alt="Toyota Avanza Travel Singgalang Jaya"
                            class="w-full h-auto object-cover aspect-[4/3] transform hover:scale-105 transition-transform duration-700"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="fitur" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <!-- Sparkles Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3.091 15.1l5.096-.813L9 9.187l.813 5.096 5.096.813-5.096.813zM19.066 5.387l-.5.92-.92.5.92.5.5.92.5-.92.92-.5-.92-.5-.5-.92zM13.562 3.125l-.25.46-.46.25.46.25.25.46.25-.46.46-.25-.46-.25-.25-.46z"></path>
                    </svg>
                    Keunggulan Kami
                </span>
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4 leading-tight">
                    Kenapa Memilih Singgalang Jaya Travel?
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Layanan travel nyaman, aman, dan profesional untuk setiap perjalanan Anda.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-blue-50 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative mb-7">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform duration-300">
                            <!-- MousePointerClick Icon -->
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6l-5.072-1.358a1.2 1.2 0 010-2.316L21.672 8.958a1.2 1.2 0 011.37 1.37l-3.968 13.06a1.2 1.2 0 01-2.032.284zM10.5 10.5L3 18m0 0h5m-5 0v-5"></path>
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
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-blue-50 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative mb-7">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform duration-300">
                            <!-- Clock Icon -->
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-blue-50 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative mb-7">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform duration-300">
                            <!-- MapPin Icon -->
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"></path>
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
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300 p-8 group relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-40 h-40 rounded-full bg-blue-50 opacity-0 group-hover:opacity-100 blur-2xl transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative mb-7">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform duration-300">
                            <!-- UserCheck Icon -->
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

            <!-- Stats Band -->
            <div class="mt-16 rounded-2xl bg-slate-900 relative overflow-hidden shadow-[0_25px_60px_rgb(15,23,42,0.25)]">
                <!-- Inner dark GlowBackground -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
                    <div class="absolute top-[-20%] right-[-5%] w-[520px] h-[520px] rounded-full blur-[120px] opacity-[0.25] bg-[#4F46E5]"></div>
                    <div class="absolute bottom-[-15%] left-[8%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.18] bg-[#0EA5E9]"></div>
                </div>

                <div class="relative z-10 grid grid-cols-2 lg:grid-cols-4 divide-y divide-x divide-white/10 lg:divide-y-0">
                    <!-- Stat 1 -->
                    <div class="p-8 lg:p-10 flex flex-col items-center text-center gap-2">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center mb-1">
                            <!-- Users Icon -->
                            <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">12.000+</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Penumpang Puas</p>
                    </div>
                    <!-- Stat 2 -->
                    <div class="p-8 lg:p-10 flex flex-col items-center text-center gap-2">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center mb-1">
                            <!-- Star Icon -->
                            <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.196-.396-.65-.396-.847 0L8.14 8.78l-5.83.826c-.439.062-.616.6-.297.913l4.22 4.112-1.002 5.795c-.075.438.384.77.778.558l5.222-2.747 5.22 2.746c.395.208.854-.124.778-.558l-1.001-5.795 4.22-4.112c.319-.313.142-.851-.297-.913l-5.83-.828-2.523-5.283z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">4.9 / 5</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Rating Pelanggan</p>
                    </div>
                    <!-- Stat 3 -->
                    <div class="p-8 lg:p-10 flex flex-col items-center text-center gap-2">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center mb-1">
                            <!-- Route Icon -->
                            <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75h3.75a8.25 8.25 0 018.25 8.25v3.75m-18 0V15a8.25 8.25 0 018.25-8.25M2.25 10.5h3.75a3.75 3.75 0 013.75 3.75v3.75m10.5-12h3.75M2.25 15h3.75m0-4.5h3.75"></path>
                            </svg>
                        </div>
                        <p class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">2 Rute</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Pulang & Pergi</p>
                    </div>
                    <!-- Stat 4 -->
                    <div class="p-8 lg:p-10 flex flex-col items-center text-center gap-2">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10 flex items-center justify-center mb-1">
                            <!-- Clock Icon -->
                            <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">99%</p>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Tepat Waktu</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SCHEDULES SECTION -->
    <section id="jadwal" class="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                        <!-- CalendarDays Icon -->
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008z"></path>
                        </svg>
                        Jadwal Keberangkatan
                    </span>
                    <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4 leading-tight">
                        Jadwal Keberangkatan Terkini
                    </h2>
                    <p class="text-slate-500 text-lg md:text-xl font-medium">
                        Pilih rute dan waktu perjalanan yang sesuai dengan rencana Anda.
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
                <div class="bg-white rounded-2xl border border-slate-200/60 p-12 text-center shadow-sm">
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
                            $fillPercentage = ($schedule->kuota > 0) ? ($bookedSeats / $schedule->kuota) * 100 : 0;
                            $isMorning = Str::lower($schedule->shift) === 'pagi';
                            $isFull = $sisaKursi <= 0;
                        @endphp
                        
                        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col p-6 sm:p-8 {{ $isFull ? 'opacity-80' : '' }}">
                            <div class="absolute top-0 right-0 w-64 h-64 {{ $isMorning ? 'bg-blue-50/60' : 'bg-indigo-50/60' }} rounded-full blur-3xl -z-0 pointer-events-none transition-colors"></div>

                            <div class="relative z-10 flex-1 flex flex-col">
                                <!-- Route & Badge -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                                    <div class="flex items-center flex-wrap gap-2 md:gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $isMorning ? 'bg-blue-600 shadow-[0_0_0_4px_rgb(37,99,235,0.12)]' : 'bg-indigo-600 shadow-[0_0_0_4px_rgb(79,70,229,0.12)]' }}"></span>
                                            <span class="text-slate-900 font-extrabold text-lg tracking-tight">{{ $schedule->rute->asal }}</span>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-400 bg-white"></span>
                                            <span class="text-slate-900 font-extrabold text-lg tracking-tight">{{ $schedule->rute->tujuan }}</span>
                                        </div>
                                    </div>
                                    <div class="{{ $isMorning ? 'bg-blue-50 text-blue-700 border-blue-100/50' : 'bg-indigo-50 text-indigo-700 border-indigo-100/50' }} px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-2 border w-fit">
                                        @if($isMorning)
                                            <!-- Sun icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                                            </svg>
                                        @else
                                            <!-- Moon icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.009c-.13-.08-.28-.1-.42-.05a8.775 8.775 0 01-11.48-11.48c.05-.14.03-.29-.05-.42a.501.501 0 00-.77-.08 10.25 10.25 0 1012.8 12.8.501.501 0 00-.08-.77z"></path>
                                            </svg>
                                        @endif
                                        Shift {{ $schedule->shift }}
                                    </div>
                                </div>

                                <!-- Time & Seats -->
                                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-4">
                                    <div>
                                        <p class="text-slate-400 text-[10px] font-bold mb-2 uppercase tracking-widest">Waktu Keberangkatan</p>
                                        <h3 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tighter">
                                            {{ $schedule->jam_berangkat instanceof \DateTime ? $schedule->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }}
                                        </h3>
                                    </div>
                                    
                                    <div class="w-full sm:w-56 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                        <div class="flex justify-between items-end mb-3">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Sisa Kursi</span>
                                            <span class="text-xl font-extrabold leading-none {{ $sisaKursi <= 1 ? 'text-rose-500' : 'text-blue-600' }}">{{ $sisaKursi }}</span>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                            <div class="h-full transition-all duration-1000 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-gradient-to-r from-blue-600 to-indigo-600' }}" style="width: {{ $fillPercentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between mt-2">
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $schedule->tanggal_keberangkatan->format('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Kap: {{ $schedule->kuota }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket Divider -->
                                <div class="relative -mx-6 sm:-mx-8 my-8">
                                    <div class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-slate-50 rounded-full border border-slate-200/60 z-20"></div>
                                    <div class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-slate-50 rounded-full border border-slate-200/60 z-20"></div>
                                    <div class="border-t-2 border-dashed border-slate-200 w-full relative z-10"></div>
                                </div>

                                <!-- Vehicle & Price Area -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100 shrink-0">
                                            <!-- Car Icon -->
                                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <p class="text-slate-900 font-extrabold text-sm">Toyota Avanza</p>
                                                <!-- CheckCircle2 icon -->
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 text-[11px] font-semibold flex items-center gap-1.5">
                                                Full AC • Door-to-Door
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1">Harga per kursi</p>
                                        <p class="text-slate-900 font-extrabold text-2xl tracking-tight">Rp {{ number_format($schedule->rute->tarif, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if($sisaKursi > 0)
                                <a href="{{ Route::has('booking.create') ? route('booking.create', ['jadwal_id' => $schedule->id]) : '#' }}" 
                                   class="w-full h-14 bg-blue-800 hover:bg-blue-900 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-800/10 active:scale-[0.98] flex justify-center items-center gap-2 relative z-10">
                                    Booking Sekarang
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                    </svg>
                                </a>
                            @else
                                <div class="w-full h-14 bg-slate-100 text-slate-400 rounded-xl font-semibold flex justify-center items-center gap-2 cursor-not-allowed">
                                    <!-- AlertCircle Icon -->
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Jadwal Penuh
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- FLEET SECTION (ARMADA) -->
    <section id="armada" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <!-- Car Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                    </svg>
                    Armada Kami
                </span>
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4 leading-tight">
                    Armada Travel Terawat
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Kendaraan ber-AC, bersih, dan terawat untuk kenyamanan perjalanan Anda.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @php
                    $fleetData = collect($drivers ?? [])->map(fn($d) => [
                        'id' => $d->id,
                        'name' => $d->armada->nama_mobil ?? 'Toyota Avanza',
                        'plate' => $d->armada->nomor_plat ?? '-',
                        'capacity' => 'Maks. ' . ($d->armada->kapasitas ?? 5) . ' Penumpang',
                        'route' => 'Padang Panjang ↔ Pekanbaru',
                        'status' => $d->dynamic_status === 'tersedia' ? 'Tersedia' : ($d->dynamic_status === 'sedang_bertugas' ? 'Sedang Bertugas' : 'Tidak Aktif')
                    ])->toArray();

                    if (empty($fleetData)) {
                        $fleetData = [
                            ['id' => 1, 'name' => 'Toyota Avanza', 'plate' => 'BA 1234 AB', 'capacity' => 'Maks. 5 Penumpang', 'route' => 'Padang Panjang ↔ Pekanbaru', 'status' => 'Tersedia'],
                            ['id' => 2, 'name' => 'Toyota Avanza', 'plate' => 'BA 1567 CD', 'capacity' => 'Maks. 5 Penumpang', 'route' => 'Padang Panjang ↔ Pekanbaru', 'status' => 'Tersedia'],
                            ['id' => 3, 'name' => 'Toyota Avanza', 'plate' => 'BM 2089 EF', 'capacity' => 'Maks. 5 Penumpang', 'route' => 'Pekanbaru ↔ Padang Panjang', 'status' => 'Tersedia'],
                            ['id' => 4, 'name' => 'Toyota Avanza', 'plate' => 'BM 2310 GH', 'capacity' => 'Maks. 5 Penumpang', 'route' => 'Pekanbaru ↔ Padang Panjang', 'status' => 'Tersedia'],
                        ];
                    }
                @endphp
                @foreach ($fleetData as $car)
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.10)] hover:-translate-y-1.5 transition-all duration-300 p-3 group flex flex-col">
                        <!-- Image & Floating Badge -->
                        <div class="relative h-48 w-full rounded-2xl overflow-hidden mb-5 bg-slate-100">
                            <img 
                                src="https://images.unsplash.com/photo-1596429916858-6f97b5b9cf48?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3aGl0ZSUyMHRveW90YSUyMGF2YW56YSUyMGNhciUyMG9uJTIwc3RyZWV0fGVufDF8fHx8MTc3OTExMjczNHww&ixlib=rb-4.1.0&q=80&w=1080" 
                                alt="{{ $car['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                            <div class="absolute top-3 right-3 bg-white/85 backdrop-blur-md px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 border border-white/50">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-extrabold text-slate-800 uppercase tracking-wider">{{ $car['status'] }}</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="px-3 flex-grow flex flex-col">
                            <div class="flex items-center justify-between mb-1.5">
                                <h4 class="text-lg font-extrabold text-slate-900 tracking-tight">{{ $car['name'] }}</h4>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 border border-slate-100 px-2 py-1 rounded-md">
                                    {{ $car['plate'] }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 mb-5 mt-2">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100">
                                    <!-- Users Icon -->
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                    </svg>
                                    {{ $car['capacity'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mx-3 border-t border-dashed border-slate-200 pt-4 mt-1 mb-2 flex items-center gap-2 text-slate-500">
                            <!-- Route icon -->
                            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75h3.75a8.25 8.25 0 018.25 8.25v3.75m-18 0V15a8.25 8.25 0 018.25-8.25M2.25 10.5h3.75a3.75 3.75 0 013.75 3.75v3.75m10.5-12h3.75M2.25 15h3.75m0-4.5h3.75"></path>
                            </svg>
                            <span class="text-xs font-semibold truncate">{{ $car['route'] }}</span>
                            <!-- ShieldCheck icon -->
                            <svg class="w-4 h-4 text-emerald-500 ml-auto shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CHARTER SECTION -->
    <section id="charter" class="py-24 md:py-32 bg-slate-50 relative overflow-hidden">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    
                    <!-- Left Content -->
                    <div class="flex-1 p-6 sm:p-10 md:p-16 lg:p-20 flex flex-col justify-center">
                        <span class="inline-flex items-center gap-2 bg-amber-50 text-amber-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-[0.12em] w-fit mb-6">
                            <!-- CarFront icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                            </svg>
                            Layanan Privat
                        </span>
                        
                        <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-6 leading-[1.1]">
                            Sewa Mobil <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Charter</span>
                        </h2>
                        <p class="text-slate-500 text-lg md:text-xl font-medium mb-8 leading-relaxed">
                            Tersedia layanan charter mobil untuk kebutuhan perjalanan pribadi dan rombongan ke mana saja.
                        </p>

                        <div class="grid sm:grid-cols-3 gap-4 mb-10">
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-200/60 mb-3">
                                    <!-- Users Icon -->
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-extrabold text-slate-900">Rombongan</p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Cocok untuk keluarga & grup</p>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-200/60 mb-3">
                                    <!-- MapPinned Icon -->
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75h3.75a8.25 8.25 0 018.25 8.25v3.75m-18 0V15a8.25 8.25 0 018.25-8.25M2.25 10.5h3.75a3.75 3.75 0 013.75 3.75v3.75m10.5-12h3.75M2.25 15h3.75m0-4.5h3.75"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-extrabold text-slate-900">Rute Bebas</p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Tentukan tujuan sendiri</p>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm border border-slate-200/60 mb-3">
                                    <!-- Clock Icon -->
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-extrabold text-slate-900">Fleksibel</p>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Jadwal sesuai kebutuhan</p>
                            </div>
                        </div>

                        <div class="bg-blue-50/60 rounded-2xl p-5 flex items-start gap-4 border border-blue-100 mb-10">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm border border-blue-100">
                                <!-- Info Icon -->
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                                </svg>
                            </div>
                            <p class="text-slate-700 font-semibold leading-snug pt-2 text-sm">
                                Pemesanan charter dilakukan langsung melalui admin via WhatsApp.
                            </p>
                        </div>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20layanan%20charter%20Singgalang%20Jaya." 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="bg-[#25D366] hover:bg-[#1ebe5d] text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-[#25D366]/25 active:scale-[0.98] w-fit flex items-center gap-2 hover:-translate-y-0.5">
                            <!-- MessageCircle Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"></path>
                            </svg>
                            Hubungi Admin
                        </a>
                    </div>

                    <!-- Right Image -->
                    <div class="flex-1 bg-slate-100 relative min-h-[350px] lg:min-h-full">
                        <img 
                            src="https://images.unsplash.com/photo-1650807486050-a142ea418b19?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3aGl0ZSUyMHRveW90YSUyMGF2YW56YSUyMG1vZGVybiUyMGNhcnxlbnwxfHx8fDE3NzkxMTU3NzF8MA&ixlib=rb-4.1.0&q=80&w=1080" 
                            alt="Charter Toyota Avanza Singgalang Jaya"
                            class="absolute inset-0 w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/30 via-transparent to-transparent pointer-events-none"></div>
                    </div>

                </div>
            </div>
        </div>
        <!-- TESTIMONIALS SECTION -->
    <section id="testimoni" class="py-24 md:py-32 bg-slate-50 relative overflow-hidden border-t border-slate-200/50">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10" 
             x-data="{
                active: 0,
                items: {{ $ratings->count() }},
                visibleCount: 3,
                autoPlayInterval: null,
                touchStartX: 0,
                init() {
                    this.updateVisibleCount();
                    window.addEventListener('resize', () => this.updateVisibleCount());
                    this.play();
                },
                updateVisibleCount() {
                    if (window.innerWidth >= 1024) {
                        this.visibleCount = 3;
                    } else if (window.innerWidth >= 768) {
                        this.visibleCount = 2;
                    } else {
                        this.visibleCount = 1;
                    }
                    if (this.active > this.maxIndex()) {
                        this.active = this.maxIndex();
                    }
                },
                maxIndex() {
                    return Math.max(0, this.items - this.visibleCount);
                },
                next() {
                    if (this.active >= this.maxIndex()) {
                        this.active = 0;
                    } else {
                        this.active++;
                    }
                },
                prev() {
                    if (this.active <= 0) {
                        this.active = this.maxIndex();
                    } else {
                        this.active--;
                    }
                },
                play() {
                    this.pause();
                    this.autoPlayInterval = setInterval(() => {
                        this.next();
                    }, 5000);
                },
                pause() {
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                    }
                }
             }" 
             x-init="init()"
             @mouseenter="pause()" 
             @mouseleave="play()"
             @touchstart="touchStartX = $event.touches[0].clientX"
             @touchend="if (touchStartX - $event.changedTouches[0].clientX > 50) next(); if ($event.changedTouches[0].clientX - touchStartX > 50) prev();"
        >
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <!-- MessageSquareHeart Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.963 15.277a1.597 1.597 0 001.596 0l7.101-4.058a1.596 1.596 0 00.803-1.383V4.832a1.597 1.597 0 00-.803-1.383l-7.1-4.059a1.596 1.596 0 00-1.597 0L4.81 3.45a1.596 1.596 0 00-.803 1.383v5.004c0 .588.324 1.127.803 1.383l7.1 4.059z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                    </svg>
                    Testimoni
                </span>
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4 leading-tight">
                    Apa Kata Penumpang Kami
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Ribuan pelanggan telah mempercayakan perjalanannya bersama kami.
                </p>
            </div>

            @if($ratings->isEmpty())
                <div class="text-center py-16 bg-white border border-slate-200/60 rounded-3xl p-8 max-w-xl mx-auto shadow-sm">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.977-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum ada testimoni pelanggan.</h3>
                    <p class="text-slate-400 font-semibold mt-1 text-sm">Jadilah pelanggan pertama yang memberikan pengalaman Anda.</p>
                </div>
            @else
                <!-- Slider Outer Container -->
                <div class="relative overflow-hidden py-4 -mx-3 px-3">
                    <!-- Slider Track -->
                    <div class="flex transition-transform duration-500 ease-out" 
                         :style="'transform: translateX(-' + (active * (100 / visibleCount)) + '%);'">
                        
                        @foreach($ratings as $rating)
                            <div class="px-3 shrink-0 w-full md:w-1/2 lg:w-1/3">
                                <div class="bg-white border border-slate-200/60 rounded-3xl p-8 relative flex flex-col justify-between h-full shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_45px_rgb(37,99,235,0.08)] hover:-translate-y-1.5 transition-all duration-300">
                                    <div>
                                        <!-- Stars -->
                                        <div class="flex items-center gap-1 mb-5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        
                                        <!-- Review Comment -->
                                        <p class="text-slate-600 italic text-sm leading-relaxed mb-6 font-medium">
                                            "{{ $rating->ulasan }}"
                                        </p>
                                    </div>
                                    
                                    <!-- User Info & Route -->
                                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100 mt-auto">
                                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-extrabold text-xs uppercase shrink-0 shadow-md">
                                            {{ substr($rating->pelanggan->nama, 0, 2) }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-extrabold text-slate-900">{{ $rating->pelanggan->nama }}</h4>
                                            <p class="text-[10px] font-black text-slate-450 uppercase tracking-wider mt-0.5">
                                                {{ $rating->booking->jadwal->rute->asal }} &rarr; {{ $rating->booking->jadwal->rute->tujuan }}
                                            </p>
                                            <p class="text-[9px] font-semibold text-slate-350 mt-0.5">
                                                {{ $rating->booking->jadwal->tanggal_keberangkatan->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Navigation Controls (Dots) -->
                <div class="flex justify-center items-center gap-2.5 mt-8" x-show="maxIndex() > 0">
                    <template x-for="index in Array.from({length: maxIndex() + 1}, (_, i) => i)">
                        <button @click="active = index; play()" 
                                class="w-2.5 h-2.5 rounded-full transition-all duration-300 focus:outline-none"
                                :class="active === index ? 'bg-blue-600 w-6' : 'bg-slate-200 hover:bg-slate-350'"></button>
                    </template>
                </div>
            @endif
        </div>
    </section>>
    </section>

    <!-- CONTACT SECTION -->
    <section id="kontak" class="py-24 md:py-32 bg-white relative overflow-hidden">
        <!-- GlowBackground Variant: Section -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-[6%] right-[14%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.05] bg-[#4F46E5]"></div>
            <div class="absolute bottom-[14%] left-[4%] w-[360px] h-[360px] rounded-full blur-[100px] opacity-[0.04] bg-[#0EA5E9]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <!-- Headset Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z"></path>
                    </svg>
                    Hubungi Kami
                </span>
                <h2 class="text-slate-900 font-extrabold text-3xl md:text-5xl tracking-tight mb-4 leading-tight">
                    Butuh Bantuan? Kami Siap Membantu
                </h2>
                <p class="text-slate-500 text-lg md:text-xl font-medium">
                    Tim kami siap membantu perjalanan Anda setiap hari.
                </p>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                <!-- Contact Cards Grid -->
                <div class="lg:col-span-7 grid sm:grid-cols-2 gap-6">
                    @php
                        $contacts = [
                            [
                                'icon' => 'phone',
                                'label' => 'WhatsApp Admin',
                                'value' => '+62 812-3456-7890',
                                'link' => 'https://wa.me/6281234567890',
                                'color' => 'bg-emerald-50 text-emerald-600',
                                'hover' => 'hover:shadow-[0_20px_40px_rgb(16,185,129,0.12)] hover:-translate-y-1',
                            ],
                            [
                                'icon' => 'instagram',
                                'label' => 'Instagram',
                                'value' => '@singgalangjayatravel',
                                'link' => '#',
                                'color' => 'bg-pink-50 text-pink-600',
                                'hover' => 'hover:shadow-[0_20px_40px_rgb(236,72,153,0.12)] hover:-translate-y-1',
                            ],
                            [
                                'icon' => 'mail',
                                'label' => 'Email',
                                'value' => 'hello@singgalangjaya.com',
                                'link' => 'mailto:hello@singgalangjaya.com',
                                'color' => 'bg-blue-50 text-blue-600',
                                'hover' => 'hover:shadow-[0_20px_40px_rgb(37,99,235,0.12)] hover:-translate-y-1',
                            ]
                        ];
                    @endphp
                    @foreach ($contacts as $item)
                        <a href="{{ $item['link'] }}" 
                           target="{{ Str::startsWith($item['link'], 'http') ? '_blank' : '_self' }}"
                           rel="noreferrer"
                           class="bg-white rounded-2xl border border-slate-200/60 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group flex flex-col items-start gap-6 {{ $item['hover'] }}">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ $item['color'] }}">
                                @if($item['icon'] === 'phone')
                                    <!-- Phone Icon -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a20.373 20.373 0 01-9.357-9.357c-.155-.44-.01-1.29.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                                    </svg>
                                @elseif($item['icon'] === 'instagram')
                                    <!-- Instagram Icon -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                    </svg>
                                @else
                                    <!-- Mail Icon -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.009c-.13-.08-.28-.1-.42-.05a8.775 8.775 0 01-11.48-11.48c.05-.14.03-.29-.05-.42a.501.501 0 00-.77-.08 10.25 10.25 0 1012.8 12.8.501.501 0 00-.08-.77z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-500 mb-1">{{ $item['label'] }}</p>
                                <p class="text-lg font-bold text-slate-900">{{ $item['value'] }}</p>
                            </div>
                            <div class="mt-auto pt-4 flex items-center gap-2 text-sm font-bold text-slate-400 group-hover:text-blue-600 transition-colors">
                                Hubungi Sekarang 
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Address Card -->
                <div class="lg:col-span-5 bg-slate-900 rounded-2xl p-10 lg:p-12 flex flex-col justify-between text-white relative overflow-hidden group shadow-[0_25px_60px_rgb(15,23,42,0.25)]">
                    <!-- Inner dark GlowBackground -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
                        <div class="absolute top-[-20%] right-[-5%] w-[520px] h-[520px] rounded-full blur-[120px] opacity-[0.25] bg-[#4F46E5]"></div>
                        <div class="absolute bottom-[-15%] left-[8%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.18] bg-[#0EA5E9]"></div>
                    </div>

                    <div class="relative z-10 space-y-6">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <!-- MapPin Icon -->
                            <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"></path>
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
                        <p class="text-slate-400 text-sm font-bold tracking-wide uppercase">Buka Setiap Hari: 06:00 – 22:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BANNER SECTION -->
    <section class="py-20 md:py-24 relative bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="relative rounded-2xl bg-slate-900 overflow-hidden shadow-[0_30px_70px_rgb(15,23,42,0.30)]">
                <!-- Inner dark GlowBackground -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none -z-0">
                    <div class="absolute top-[-20%] right-[-5%] w-[520px] h-[520px] rounded-full blur-[120px] opacity-[0.25] bg-[#4F46E5]"></div>
                    <div class="absolute bottom-[-15%] left-[8%] w-[420px] h-[420px] rounded-full blur-[110px] opacity-[0.18] bg-[#0EA5E9]"></div>
                </div>

                <div class="relative z-10 px-8 py-14 md:px-16 md:py-20 flex flex-col lg:flex-row items-center justify-between gap-10">
                    <div class="max-w-2xl text-center lg:text-left">
                        <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/10 text-sky-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-[0.12em] mb-6">
                            <!-- Sparkles Icon -->
                            <svg class="w-4 h-4 text-sky-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3.091 15.1l5.096-.813L9 9.187l.813 5.096 5.096.813-5.096.813zM19.066 5.387l-.5.92-.92.5.92.5.5.92.5-.92.92-.5-.92-.5-.5-.92zM13.562 3.125l-.25.46-.46.25.46.25.25.46.25-.46.46-.25-.46-.25-.25-.46z"></path>
                            </svg>
                            Mulai Perjalanan Anda
                        </span>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight leading-[1.1] mb-4">
                            Siap Berangkat Bersama <br class="hidden md:block" />
                            <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Singgalang Jaya Travel?</span>
                        </h2>
                        <p class="text-slate-300 text-lg font-medium">
                            Pesan kursi Anda sekarang dengan DP mulai Rp50.000 dan nikmati layanan door-to-door.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 shrink-0 w-full sm:w-auto">
                        <a href="{{ Route::has('booking.create') ? route('booking.create') : '#' }}" 
                           class="bg-blue-800 hover:bg-blue-900 text-white px-8 h-14 rounded-xl font-bold text-base transition-all shadow-lg shadow-blue-800/20 flex items-center justify-center gap-2 group w-full sm:w-auto active:scale-[0.98]">
                            Booking Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path>
                            </svg>
                        </a>
                        <a href="https://wa.me/6281234567890" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center justify-center gap-2 h-14 px-8 text-base font-semibold rounded-xl bg-white/10 text-white border border-white/15 hover:bg-white/20 backdrop-blur-sm transition-all active:scale-[0.98] w-full sm:w-auto">
                            <!-- PhoneCall Icon -->
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a20.373 20.373 0 01-9.357-9.357c-.155-.44-.01-1.29.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                            </svg>
                            Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
