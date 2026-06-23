<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Driver Panel</title>

    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased font-poppins text-slate-800 bg-slate-50">
    <div x-data="{ sidebarMobileOpen: false, profileDropdownOpen: false, logoutModalOpen: false }" class="min-h-screen flex relative overflow-x-hidden">
        
        <!-- Sidebar - Desktop (Static) -->
        <aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 lg:bg-[#0B1329] lg:text-slate-300 lg:z-50 lg:border-r lg:border-white/5 lg:shadow-2xl">
            <x-sidebar-driver />
        </aside>

        <!-- Sidebar - Mobile (Drawer Overlay) -->
        <div class="fixed inset-0 z-[100] lg:hidden"
             x-show="sidebarMobileOpen"
             x-cloak
             x-transition:enter="transition-all duration-300 ease-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-all duration-300 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Dark Overlay background -->
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"
                 @click="sidebarMobileOpen = false"></div>
            
            <!-- Drawer Content -->
            <aside class="absolute inset-y-0 left-0 w-72 bg-[#0B1329] text-slate-300 shadow-2xl flex flex-col"
                   x-show="sidebarMobileOpen"
                   x-transition:enter="transition-transform duration-300 ease-out"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition-transform duration-300 ease-in"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">
                
                <!-- Close Button -->
                <button @click="sidebarMobileOpen = false"
                        class="absolute top-6 right-6 p-2 text-slate-400 hover:text-white hover:bg-white/10 rounded-xl transition-all lg:hidden z-50">
                    <!-- X Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <x-sidebar-driver />
            </aside>
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col lg:pl-72 min-h-screen min-w-0 w-full max-w-full overflow-x-hidden">
            
            <!-- Top Navbar -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Hamburger menu button (mobile) -->
                    <button @click="sidebarMobileOpen = true"
                            class="lg:hidden p-3 text-slate-500 hover:bg-slate-100 rounded-xl transition-all active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title / Branding -->
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-[0.2em] hidden sm:block">Singgalang Jaya Travel</h2>
                </div>

                <!-- Right Nav Section -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <div @click="profileDropdownOpen = !profileDropdownOpen"
                             class="flex items-center gap-3 group cursor-pointer">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-black text-slate-900 leading-none mb-1 group-hover:text-blue-600 transition-colors uppercase tracking-widest">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center border border-slate-800 shadow-xl shadow-slate-900/10 relative">
                                <!-- User Icon -->
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                            </div>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="profileDropdownOpen"
                             x-cloak
                             @click.outside="profileDropdownOpen = false"
                             class="absolute right-0 mt-4 w-56 bg-white rounded-[2rem] shadow-2xl border border-slate-100 py-4 z-[120]"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            
                            <div class="px-6 py-2 mb-2 border-b border-slate-50">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Akun Driver</p>
                            </div>
                            
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <!-- User circle SVG -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[11px] font-black uppercase tracking-widest">Profil Saya</span>
                            </a>
                            
                            <a href="{{ route('profile.edit') }}?tab=password" class="flex items-center gap-3 px-6 py-3 text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <!-- Key Icon SVG -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-2 4a5 5 0 110-10 5 5 0 010 10zM12 14l-4 4m0 0l-2-2m2 2l2 2m7-14V4a1 1 0 112 0v3m-2 0h3"></path>
                                </svg>
                                <span class="text-[11px] font-black uppercase tracking-widest">Ubah Password</span>
                            </a>
                            
                            <button @click="logoutModalOpen = true; profileDropdownOpen = false"
                                    class="w-full flex items-center gap-3 px-6 py-3 text-rose-500 hover:bg-rose-50 transition-colors border-t border-slate-50 mt-2 text-left">
                                <!-- LogOut Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-[11px] font-black uppercase tracking-widest">Logout</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-10 max-w-[1600px] mx-auto w-full">
                @yield('content')
            </main>
        </div>

        <!-- Logout Confirmation Modal -->
        <div class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
             x-show="logoutModalOpen"
             x-cloak
             x-transition:enter="transition-all duration-300 ease-out"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-all duration-300 ease-in"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white w-full max-w-sm rounded-[3rem] shadow-2xl overflow-hidden p-10 text-center"
                 @click.outside="logoutModalOpen = false"
                 x-show="logoutModalOpen"
                 x-transition:enter="transition-all duration-300 ease-out"
                 x-transition:enter-start="transform scale-95"
                 x-transition:enter-end="transform scale-100"
                 x-transition:leave="transition-all duration-300 ease-in"
                 x-transition:leave-start="transform scale-100"
                 x-transition:leave-end="transform scale-95">
                
                <div class="w-20 h-20 bg-rose-50 rounded-[2.5rem] flex items-center justify-center text-rose-500 mx-auto mb-6 shadow-xl shadow-rose-500/10">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Konfirmasi Logout</h3>
                <p class="text-sm font-bold text-slate-400 mb-8 px-4 leading-relaxed">Apakah Anda yakin ingin keluar dari Panel Driver?</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <button @click="logoutModalOpen = false"
                            class="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    
                    <button @click="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="py-4 bg-rose-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-rose-500/20 hover:bg-rose-600 transition-all">
                        Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- Logout Form (standard Laravel Breeze logout form) -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    @livewireScripts
</body>
</html>
