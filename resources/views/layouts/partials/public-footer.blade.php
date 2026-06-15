<footer class="bg-white border-t border-slate-200 pt-20 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
            
            <!-- Column 1: Brand & Description -->
            <div class="lg:pr-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight leading-none">
                        Singgalang Jaya Travel
                    </h3>
                </div>
                <p class="text-slate-500 font-medium leading-relaxed text-sm">
                    Layanan travel Padang Panjang &harr; Pekanbaru dengan sistem booking online.
                </p>
            </div>

            <!-- Column 2: Menu -->
            <div>
                <h4 class="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Menu</h4>
                <ul class="space-y-4">
                    @php
                        $isHome = Request::routeIs('home');
                        $menuItems = [
                            ['name' => 'Home', 'href' => $isHome ? '#home' : route('home') . '#home'],
                            ['name' => 'Jadwal', 'href' => $isHome ? '#jadwal' : route('home') . '#jadwal'],
                            ['name' => 'Armada', 'href' => $isHome ? '#armada' : route('home') . '#armada'],
                            ['name' => 'Charter', 'href' => $isHome ? '#charter' : route('home') . '#charter'],
                            ['name' => 'Kontak', 'href' => $isHome ? '#kontak' : route('home') . '#kontak'],
                        ];
                    @endphp
                    @foreach ($menuItems as $item)
                        <li>
                            <a href="{{ $item['href'] }}" class="text-slate-500 hover:text-blue-600 font-medium transition-colors text-sm">
                                {{ $item['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 3: Kontak -->
            <div>
                <h4 class="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Kontak</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="https://wa.me/6281234567890?text=Halo%20Singgalang%20Jaya%20Travel" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            <span class="font-medium text-sm">WhatsApp</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                            </svg>
                            <span class="font-medium text-sm">Instagram</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:info@singgalangjayatravel.com" class="flex items-center gap-3 text-slate-500 hover:text-blue-600 transition-colors group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            <span class="font-medium text-sm">Email</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Alamat -->
            <div>
                <h4 class="text-slate-900 font-bold uppercase tracking-wider text-sm mb-6">Alamat</h4>
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <p class="font-medium leading-relaxed text-slate-500 text-sm">
                        Padang Panjang,<br />
                        Sumatera Barat
                    </p>
                </div>
            </div>

        </div>

        <!-- Bottom Footer & Divider -->
        <div class="border-t border-slate-200 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-slate-400 text-sm font-medium">
                &copy; {{ date('Y') }} Singgalang Jaya Travel. All rights reserved.
            </p>
            <div class="flex items-center gap-4">
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                    </svg>
                </a>
                <a href="mailto:info@singgalangjayatravel.com" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-blue-600 transition-colors" aria-label="Email">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </a>
            </div>
        </div>
        
    </div>
</footer>
