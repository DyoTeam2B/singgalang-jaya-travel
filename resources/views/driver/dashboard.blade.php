<x-driver-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black uppercase tracking-wider text-slate-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Portal Driver Singgalang Jaya Travel</p>
                        </div>
                    </div>
                    
                    <p class="text-sm text-slate-600 mb-6 font-medium">
                        Anda telah berhasil masuk ke panel operasional driver. Di sini Anda dapat memantau trip yang ditugaskan kepada Anda, melihat manifest penumpang, dan melakukan navigasi titik jemput/antar penumpang secara real-time.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Tugas Saat Ini</h4>
                            <div class="flex items-center gap-3 text-slate-500 italic text-xs font-medium">
                                <svg class="w-4 h-4 text-slate-400 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Belum ada trip aktif yang ditugaskan saat ini.
                            </div>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Informasi Kendaraan</h4>
                            @if(Auth::user()->driver)
                                <div class="space-y-2 text-xs font-bold text-slate-600">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 uppercase tracking-wider">Merk Mobil</span>
                                        <span class="text-slate-800 uppercase">{{ Auth::user()->driver->nama_mobil }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 uppercase tracking-wider">Nomor Plat</span>
                                        <span class="text-slate-800 uppercase">{{ Auth::user()->driver->nomor_plat }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400 uppercase tracking-wider">Kapasitas</span>
                                        <span class="text-slate-800 uppercase">{{ Auth::user()->driver->kapasitas_mobil }} Orang</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3 text-slate-500 italic text-xs font-medium">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Data profil driver Anda belum dilengkapi oleh admin.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-driver-layout>