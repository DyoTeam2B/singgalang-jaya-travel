<x-guest-layout full>
    <div class="min-h-screen bg-slate-50 flex font-poppins">
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 overflow-hidden flex-col justify-between p-12">
            <div class="absolute inset-0 z-0">
                <img
                    src="https://images.unsplash.com/photo-1486673748761-a8d18475c757?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixlib=rb-4.1.0&q=80&w=1080"
                    alt="Jalan lintas Sumatera"
                    class="w-full h-full object-cover opacity-40"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
            </div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group" aria-label="Singgalang Jaya Travel">
                    <span class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2l7 19-7-4-7 4 7-19Z" />
                        </svg>
                    </span>
                    <span class="flex flex-col">
                        <span class="font-extrabold text-white text-xl leading-none tracking-tight mb-0.5">Singgalang Jaya</span>
                        <span class="font-bold text-blue-400 text-[11px] uppercase tracking-[0.15em] leading-none">Travel</span>
                    </span>
                </a>
            </div>

            <div class="relative z-10 max-w-lg mt-auto">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                    Selamat Datang di <br>
                    <span class="text-blue-400">Singgalang Jaya Travel</span>
                </h1>
                <p class="text-slate-300 text-lg font-medium leading-relaxed">
                    Sistem pemesanan travel door-to-door untuk rute Padang Panjang dan Pekanbaru dengan layanan yang rapi, cepat, dan mudah dipantau.
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

            <div class="absolute top-6 right-6 lg:hidden flex items-center gap-2">
                <span class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2l7 19-7-4-7 4 7-19Z" />
                    </svg>
                </span>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-6 sm:mb-8 text-center lg:text-left mt-8 lg:mt-0">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-3">Masuk ke Akun</h2>
                    <p class="text-slate-500 font-medium text-lg">Silakan masukkan email dan kata sandi Anda.</p>
                </div>

                <div class="bg-white p-6 sm:p-10 rounded-2xl shadow-sm border border-slate-100">
                    <x-auth-session-status class="mb-5" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

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
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="nama@email.com"
                                    required
                                    autofocus
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
                                    class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-colors"
                                    placeholder="Password Anda"
                                    required
                                    autocomplete="current-password"
                                >
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-2 gap-4">
                            <label for="remember_me" class="flex items-center gap-3 cursor-pointer group">
                                <span class="relative flex items-center justify-center">
                                    <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                                    <span class="w-5 h-5 border-2 border-slate-300 rounded bg-white peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-colors group-hover:border-blue-400"></span>
                                    <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg>
                                </span>
                                <span class="text-sm font-semibold text-slate-600 select-none">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                                    Lupa Sandi?
                                </a>
                            @endif
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors active:scale-[0.98]">
                                Login
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <p class="text-sm font-semibold text-slate-500">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold transition-colors">Daftar sekarang</a>
                        </p>
                    </div>
                </div>

                <p class="text-center text-sm font-semibold text-slate-500 mt-8 px-4">
                    Admin, driver, dan pelanggan masuk menggunakan email yang terdaftar.
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
