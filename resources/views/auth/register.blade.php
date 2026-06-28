<x-guest-layout full>
    <div class="min-h-screen bg-slate-50 flex font-poppins">
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden flex-col justify-between p-12">
            <div class="absolute inset-0 z-0">
                <img
                    src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=1080"
                    alt="Armada travel di perjalanan"
                    class="w-full h-full object-cover opacity-40"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
            </div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3.5 group" aria-label="Singgalang Jaya Travel">
                    <div class="w-14 h-14 bg-blue-600 shadow-sm border border-slate-100/10 rounded-xl flex items-center justify-center p-2 group-hover:scale-105 transition-transform">
                        <img src="{{ asset('logo1.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <span class="flex flex-col justify-center">
                        <span class="font-extrabold text-white text-2xl leading-tight tracking-tight">Singgalang Jaya</span>
                        <span class="font-bold text-blue-400 text-xs uppercase tracking-[0.15em] mt-1.5 leading-none">Travel</span>
                    </span>
                </a>
            </div>

            <div class="relative z-10 max-w-lg mt-auto">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                    Mulai Perjalanan <br>
                    <span class="text-blue-400">Anda Bersama Kami</span>
                </h1>
                <p class="text-slate-300 text-lg font-medium leading-relaxed">
                    Daftar untuk memesan travel door-to-door, memilih jadwal, mengunggah DP, dan memantau status booking dari satu akun pelanggan.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 relative min-h-screen">
            <a href="{{ route('home') }}" class="absolute top-6 left-6 lg:hidden flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Kembali
            </a>

            <div class="w-full max-w-md">
                <div class="mb-6 sm:mb-8 text-center lg:text-left mt-8 lg:mt-0">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Buat Akun Baru</h2>
                    <p class="text-slate-500 font-medium text-lg">Lengkapi data di bawah untuk mendaftar.</p>
                </div>

                <div class="bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-slate-100">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label for="name" class="text-sm font-bold text-slate-700 block">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </div>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="Nama lengkap Anda"
                                    required
                                    autofocus
                                    autocomplete="name"
                                >
                            </div>
                            @error('name')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="no_hp" class="text-sm font-bold text-slate-700 block">Nomor Telepon</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.11 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.32 1.77.59 2.61a2 2 0 0 1-.45 2.11L8 9.69a16 16 0 0 0 6 6l1.25-1.25a2 2 0 0 1 2.11-.45c.84.27 1.71.47 2.61.59A2 2 0 0 1 22 16.92Z" />
                                    </svg>
                                </div>
                                <input
                                    id="no_hp"
                                    type="tel"
                                    name="no_hp"
                                    value="{{ old('no_hp') }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="081234567890"
                                    required
                                    autocomplete="tel"
                                >
                            </div>
                            @error('no_hp')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-bold text-slate-700 block">Alamat Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a2 2 0 0 1-2.06 0L2 7" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="nama@email.com"
                                    required
                                    autocomplete="username"
                                >
                            </div>
                            @error('email')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="text-sm font-bold text-slate-700 block">Kata Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="18" height="11" x="3" y="11" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="Minimal 8 karakter"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-sm font-bold text-slate-700 block">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="18" height="11" x="3" y="11" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </div>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="Ulangi kata sandi"
                                    required
                                    autocomplete="new-password"
                                >
                            </div>
                            @error('password_confirmation')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors active:scale-[0.98]">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <p class="text-sm font-semibold text-slate-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold transition-colors">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
