<nav x-data="{ isMobileMenuOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- LEFT SIDE: Brand & Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 cursor-pointer group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-extrabold text-slate-900 text-lg leading-none tracking-tight mb-0.5">
                        Singgalang Jaya
                    </span>
                    <span class="font-bold text-blue-600 text-[10px] uppercase tracking-[0.15em] leading-none">
                        Travel
                    </span>
                </div>
            </a>

            <!-- CENTER MENU: Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8">
                @php
                    $isHome = Request::routeIs('home');
                    $navLinks = [
                        ['name' => 'Home', 'href' => $isHome ? '#home' : route('home') . '#home', 'active' => $isHome && !Request::is('cek-booking')],
                        ['name' => 'Jadwal', 'href' => $isHome ? '#jadwal' : route('home') . '#jadwal', 'active' => false],
                        ['name' => 'Armada', 'href' => $isHome ? '#armada' : route('home') . '#armada', 'active' => false],
                        ['name' => 'Charter', 'href' => $isHome ? '#charter' : route('home') . '#charter', 'active' => false],
                        ['name' => 'Cek Status', 'href' => Route::has('cek-booking.index') ? route('cek-booking.index') : '#', 'active' => Request::is('cek-booking*')],
                        ['name' => 'Kontak', 'href' => $isHome ? '#kontak' : route('home') . '#kontak', 'active' => false],
                    ];
                @endphp

                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" 
                       class="text-sm font-semibold transition-colors relative group py-2 {{ $link['active'] ? 'text-blue-600' : 'text-slate-500 hover:text-blue-600' }}">
                        {{ $link['name'] }}
                        <!-- Subtle Hover Underline Indicator -->
                        <span class="absolute bottom-0 left-0 h-0.5 bg-blue-600 transition-all duration-300 rounded-full {{ $link['active'] ? 'w-full opacity-100' : 'w-0 opacity-0 group-hover:w-full group-hover:opacity-100' }}"></span>
                    </a>
                @endforeach
            </div>

            <!-- RIGHT SIDE: Action Buttons -->
            <div class="hidden lg:flex items-center gap-3 shrink-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-bold text-white bg-blue-800 px-6 py-2.5 rounded-xl hover:bg-blue-900 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 text-sm font-bold text-slate-700 bg-white border border-slate-200 px-5 py-2.5 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-2 text-sm font-bold text-white bg-slate-900 px-5 py-2.5 rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                        </svg>
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="lg:hidden flex items-center">
                <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="p-2 -mr-2 rounded-lg text-slate-500 hover:bg-slate-50 transition-colors"
                        aria-label="Toggle menu">
                    <svg x-show="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>
                    </svg>
                    <svg x-show="isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" style="display: none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div x-show="isMobileMenuOpen" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-white border-t border-slate-100 absolute w-full px-4 pt-4 pb-6 shadow-xl shadow-slate-900/5"
         style="display: none;">
        <div class="flex flex-col space-y-1 mb-6">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" 
                   @click="isMobileMenuOpen = false"
                   class="px-4 py-3 rounded-xl text-base font-semibold transition-colors {{ $link['active'] ? 'text-blue-600 bg-blue-50' : 'text-slate-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    {{ $link['name'] }}
                </a>
            @endforeach
        </div>
        <div class="flex flex-col px-2 border-t border-slate-100 pt-6 gap-2">
            @auth
                <a href="{{ route('dashboard') }}" @click="isMobileMenuOpen = false" class="w-full flex justify-center items-center gap-2 text-sm font-bold text-white bg-blue-800 px-5 py-3 rounded-xl hover:bg-blue-900 transition-all shadow-sm">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" @click="isMobileMenuOpen = false" class="w-full flex justify-center items-center gap-2 text-sm font-bold text-slate-700 bg-white border border-slate-200 px-5 py-3 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Login
                </a>
                <a href="{{ route('register') }}" @click="isMobileMenuOpen = false" class="w-full flex justify-center items-center gap-2 text-sm font-bold text-white bg-slate-900 px-5 py-3 rounded-xl hover:bg-slate-800 transition-all shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                    </svg>
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>
