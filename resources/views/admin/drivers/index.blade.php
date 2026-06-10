@extends('layouts.admin')

@section('content')
<div x-data="{ 
    isAddModalOpen: @json($errors->any() && old('action_type') === 'create'), 
    isEditModalOpen: @json($errors->any() && old('action_type') === 'edit') 
}" class="pb-8 flex flex-col h-full font-poppins relative">
    
    <!-- Top Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-1">Manajemen Driver</h1>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">
                Kelola data pengemudi dan ketersediaan armada.
            </p>
        </div>
        <button 
            @click="isAddModalOpen = true"
            class="flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-white bg-slate-900 px-6 py-4 rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 whitespace-nowrap active:scale-95"
        >
            <!-- UserPlus Icon -->
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
            </svg>
            Tambah Driver
        </button>
    </div>

    <!-- Alert Message -->
    <div class="mb-6">
        <x-alert />
    </div>

    <!-- Toolbar (Search & Filter) -->
    <div class="mb-8 flex flex-col xl:flex-row xl:items-center gap-4">
        <form method="GET" action="{{ route('admin.drivers.index') }}" class="w-full flex flex-col xl:flex-row xl:items-center gap-4">
            <div class="relative w-full xl:w-[400px] shrink-0">
                <!-- Search Icon -->
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    placeholder="Cari ID Driver, Nama, Plat atau Email..." 
                    value="{{ $search }}"
                    class="w-full pl-11 pr-4 py-4 bg-white border border-slate-100 rounded-[1.5rem] text-xs font-bold focus:ring-4 focus:ring-slate-900/5 focus:border-slate-200 outline-none transition-all shadow-xl shadow-slate-900/5"
                />
            </div>

            <div class="relative w-full xl:w-64">
                <!-- Filter Icon -->
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.59l-5.432 5.432a2.25 2.25 0 00-.659 1.59v3.492a.75.75 0 01-.29.59l-2.25 1.725a.75.75 0 01-1.21-.59v-5.217c0-.596-.237-1.17-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"></path>
                </svg>
                <select 
                    name="status"
                    onchange="this.form.submit()"
                    class="w-full pl-11 pr-8 py-4 bg-white border border-slate-100 shadow-xl shadow-slate-900/5 rounded-[1.5rem] text-xs font-bold text-slate-900 appearance-none focus:ring-4 focus:ring-slate-900/5 cursor-pointer outline-none border-none"
                >
                    <option value="">Semua Status</option>
                    <option value="Tersedia" {{ $statusFilter === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Sedang Bertugas" {{ $statusFilter === 'Sedang Bertugas' ? 'selected' : '' }}>Sedang Bertugas</option>
                    <option value="Tidak Aktif" {{ $statusFilter === 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            
            <button type="submit" class="hidden">Filter</button>

            @if($search || $statusFilter)
                <a href="{{ route('admin.drivers.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition-colors py-4 px-2">
                    Reset Filter
                </a>
            @endif
        </form>
    </div>

    <!-- Data Layout (Table & Detail Column) -->
    <div class="flex flex-col xl:flex-row gap-8 items-start min-h-[500px]">
        
        <!-- Left Column: Data Table -->
        <div class="flex-1 w-full bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-50 bg-slate-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned Trip</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($drivers as $driver)
                            @php
                                $isSelected = $selectedDriver && $selectedDriver->id === $driver->id;
                                $dynamicStatus = $driver->dynamic_status;
                                $activeTrip = $driver->trips()->whereIn('status_trip', ['ready', 'berjalan'])->first();
                            @endphp
                            <tr 
                                onclick="window.location.href='{{ route('admin.drivers.index', ['selected_id' => $driver->id, 'search' => $search, 'status' => $statusFilter, 'page' => $drivers->currentPage()]) }}'"
                                class="transition-all cursor-pointer group {{ $isSelected ? 'bg-slate-50' : 'hover:bg-slate-50/50' }}"
                            >
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar Silhouette / Initials -->
                                        <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-slate-100 shadow-sm flex items-center justify-center text-blue-600 text-xs font-black uppercase">
                                            {{ substr($driver->nama_driver, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900">{{ $driver->nama_driver }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">DRV-{{ str_pad($driver->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="text-[11px] font-bold text-slate-600">{{ $driver->no_hp }}</div>
                                    <div class="text-[10px] font-medium text-slate-400">{{ $driver->user->email }}</div>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    @if($activeTrip)
                                        <div class="flex items-center gap-2 text-blue-600">
                                            <!-- Map Icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z"></path>
                                            </svg>
                                            <span class="text-xs font-black">TRP-{{ str_pad($activeTrip->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300 uppercase italic">Standby</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <x-status-badge status="{{ $dynamicStatus }}" />
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <!-- Edit Profil Trigger -->
                                        <button 
                                            @click="isEditModalOpen = true"
                                            class="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-blue-600 transition-colors shadow-sm active:scale-95"
                                            title="Edit Profil"
                                        >
                                            <!-- Edit Icon -->
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                            </svg>
                                        </button>
                                        
                                        @if($driver->status_driver === 'aktif')
                                            <!-- Deactivate / Toggle Status button -->
                                            <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <!-- Keep other fields unchanged, just toggle status to nonaktif -->
                                                <input type="hidden" name="nama_driver" value="{{ $driver->nama_driver }}">
                                                <input type="hidden" name="email" value="{{ $driver->user->email }}">
                                                <input type="hidden" name="no_hp" value="{{ $driver->no_hp }}">
                                                <input type="hidden" name="nama_mobil" value="{{ $driver->nama_mobil }}">
                                                <input type="hidden" name="nomor_plat" value="{{ $driver->nomor_plat }}">
                                                <input type="hidden" name="kapasitas_mobil" value="{{ $driver->kapasitas_mobil }}">
                                                <input type="hidden" name="status_driver" value="nonaktif">
                                                
                                                <button 
                                                    type="submit"
                                                    class="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-rose-500 transition-colors shadow-sm active:scale-95"
                                                    title="Nonaktifkan Driver"
                                                    onclick="return confirm('Apakah Anda yakin ingin menonaktifkan driver {{ $driver->nama_driver }}?');"
                                                >
                                                    <!-- Power Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Activate / Toggle Status button -->
                                            <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <!-- Keep other fields unchanged, toggle status to aktif -->
                                                <input type="hidden" name="nama_driver" value="{{ $driver->nama_driver }}">
                                                <input type="hidden" name="email" value="{{ $driver->user->email }}">
                                                <input type="hidden" name="no_hp" value="{{ $driver->no_hp }}">
                                                <input type="hidden" name="nama_mobil" value="{{ $driver->nama_mobil }}">
                                                <input type="hidden" name="nomor_plat" value="{{ $driver->nomor_plat }}">
                                                <input type="hidden" name="kapasitas_mobil" value="{{ $driver->kapasitas_mobil }}">
                                                <input type="hidden" name="status_driver" value="aktif">
                                                
                                                <button 
                                                    type="submit"
                                                    class="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-emerald-500 transition-colors shadow-sm active:scale-95"
                                                    title="Aktifkan Driver"
                                                >
                                                    <!-- Power Icon (green when inactive, click to activate) -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center text-slate-400 font-bold">
                                    <!-- AlertCircle Icon -->
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                                    </svg>
                                    Tidak ada data driver ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($drivers->hasPages())
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $drivers->links() }}
                </div>
            @endif
        </div>

        <!-- Right Column: Detail Panel -->
        @if($selectedDriver)
            @php
                $selActiveTrip = $selectedDriver->trips()->whereIn('status_trip', ['ready', 'berjalan'])->first();
            @endphp
            <div class="w-full xl:w-[400px] bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 flex flex-col overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Detail Profil Driver</h3>
                    <span class="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                        DRV-{{ str_pad($selectedDriver->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                <div class="flex-1 p-8 space-y-8">
                    <!-- Profile Avatar Header -->
                    <div class="flex flex-col items-center text-center">
                        <div class="w-28 h-28 rounded-[2.5rem] p-1 border border-slate-100 shadow-inner mb-6 flex items-center justify-center bg-blue-50 border-blue-100 text-blue-600 text-3xl font-black uppercase">
                            {{ substr($selectedDriver->nama_driver, 0, 2) }}
                        </div>
                        <h3 class="text-xl font-black text-slate-900 leading-tight mb-2">{{ $selectedDriver->nama_driver }}</h3>
                        <x-status-badge status="{{ $selectedDriver->dynamic_status }}" />
                    </div>

                    <!-- Details Card -->
                    <div class="space-y-6">
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-5">
                            <!-- Email -->
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm shrink-0">
                                    <!-- Mail Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Email</p>
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $selectedDriver->user->email }}</p>
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm shrink-0">
                                    <!-- Phone Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Nomor HP</p>
                                    <p class="text-xs font-bold text-slate-900">{{ $selectedDriver->no_hp }}</p>
                                </div>
                            </div>
                            <!-- Vehicle details -->
                            <div class="flex items-center gap-4 pt-3 border-t border-slate-100">
                                <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 shrink-0">
                                    <!-- Car Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Kendaraan</p>
                                    <p class="text-xs font-bold text-slate-900">{{ $selectedDriver->nama_mobil }} · <span class="uppercase">{{ $selectedDriver->nomor_plat }}</span></p>
                                    <p class="text-[10px] font-bold text-slate-400">Kapasitas {{ $selectedDriver->kapasitas_mobil }} penumpang</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Assignment -->
                        <div class="space-y-4">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tugas Trip</p>
                            @if($selActiveTrip)
                                <div class="p-6 bg-blue-900 rounded-[2rem] text-white shadow-xl shadow-blue-900/20 relative overflow-hidden">
                                    <div class="relative z-10">
                                        <p class="text-lg font-black leading-tight mb-1">{{ $selActiveTrip->jadwal->rute->asal }} &harr; {{ $selActiveTrip->jadwal->rute->tujuan }}</p>
                                        <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-6">TRP-{{ str_pad($selActiveTrip->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        
                                        <div class="flex items-center gap-3 bg-white/10 p-4 rounded-2xl backdrop-blur-md">
                                            <!-- Car icon -->
                                            <svg class="w-5 h-5 text-blue-200 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                                            </svg>
                                            <span class="text-[11px] font-bold">{{ $selectedDriver->nama_mobil }} (<span class="uppercase">{{ $selectedDriver->nomor_plat }}</span>)</span>
                                        </div>
                                    </div>
                                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                                </div>
                            @else
                                <div class="p-8 border-2 border-dashed border-slate-100 rounded-[2rem] flex flex-col items-center justify-center text-center opacity-50 bg-slate-50/30">
                                    <!-- Map Icon -->
                                    <svg class="w-8 h-8 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.89-2.201a1.125 1.125 0 00.607-1.006V5.49a1.125 1.125 0 00-1.503-1.035L13.5 6.75 8.163 4.414a1.125 1.125 0 00-.916 0L2.357 6.643A1.125 1.125 0 001.75 7.65V18.15a1.125 1.125 0 001.503 1.035L8.25 17.25l5.337 2.336a1.125 1.125 0 00.916 0z"></path>
                                    </svg>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver sedang standby</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50/50 border-t border-slate-100 grid grid-cols-2 gap-4">
                    <button 
                        @click="isEditModalOpen = true"
                        class="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-sm"
                    >
                        Edit Profil
                    </button>
                    
                    <!-- Deleting / Destroy form -->
                    <form action="{{ route('admin.drivers.destroy', $selectedDriver->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all active:scale-95"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data driver {{ $selectedDriver->nama_driver }} beserta akun login-nya secara permanen?');"
                        >
                            Hapus Driver
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="w-full xl:w-[400px] h-[500px] flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 opacity-40">
                <!-- User Icon -->
                <svg class="w-16 h-16 text-slate-300 mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <p class="text-sm font-black text-slate-500 uppercase tracking-[0.2em]">Pilih Driver untuk Detail</p>
            </div>
        @endif
    </div>

    <!-- Add Driver Modal -->
    <div 
        x-show="isAddModalOpen" 
        x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
        x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-all duration-300 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden"
            x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="transform scale-95"
            x-transition:enter-end="transform scale-100"
            x-transition:leave="transition-all duration-300 ease-in"
            x-transition:leave-start="transform scale-100"
            x-transition:leave-end="transform scale-95"
            @click.outside="isAddModalOpen = false"
        >
            <form method="POST" action="{{ route('admin.drivers.store') }}">
                @csrf
                <input type="hidden" name="action_type" value="create">

                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Driver Baru</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lengkapi profil dan akses login driver</p>
                    </div>
                    <button 
                        type="button"
                        @click="isAddModalOpen = false" 
                        class="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors"
                    >
                        <!-- X Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8 max-h-[60vh] overflow-y-auto no-scrollbar">
                    
                    <!-- Nama Driver -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Driver</label>
                        <div class="relative">
                            <!-- User Circle Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <input 
                                required
                                type="text" 
                                name="nama_driver"
                                value="{{ old('action_type') === 'create' ? old('nama_driver') : '' }}"
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('nama_driver') ring-2 ring-red-500 @enderror"
                                placeholder="Nama Lengkap" 
                            />
                        </div>
                        @error('nama_driver')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor HP</label>
                        <div class="relative">
                            <!-- Phone Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                            </svg>
                            <input 
                                required
                                type="tel" 
                                name="no_hp"
                                value="{{ old('action_type') === 'create' ? old('no_hp') : '' }}"
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('no_hp') ring-2 ring-red-500 @enderror"
                                placeholder="0812-xxxx-xxxx" 
                            />
                        </div>
                        @error('no_hp')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email (Login ID)</label>
                        <div class="relative">
                            <!-- Mail Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                            </svg>
                            <input 
                                required
                                type="email" 
                                name="email"
                                value="{{ old('action_type') === 'create' ? old('email') : '' }}"
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('email') ring-2 ring-red-500 @enderror"
                                placeholder="driver@singgalang.com" 
                            />
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                        <div class="relative">
                            <!-- Lock Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                            </svg>
                            <input 
                                required
                                type="password" 
                                name="password"
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('password') ring-2 ring-red-500 @enderror"
                                placeholder="••••••••" 
                            />
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Informasi Kendaraan -->
                    <div class="md:col-span-2 p-5 bg-blue-50/40 border border-blue-100 rounded-2xl space-y-4">
                        <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest flex items-center gap-2">
                            <!-- Car Icon -->
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                            </svg>
                            Informasi Kendaraan Driver
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Nama Mobil -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Mobil</label>
                                <input
                                    required
                                    type="text"
                                    name="nama_mobil"
                                    value="{{ old('action_type') === 'create' ? old('nama_mobil') : 'Toyota Avanza' }}"
                                    class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 @error('nama_mobil') border-red-500 @enderror"
                                    placeholder="Toyota Avanza"
                                />
                                @error('nama_mobil')
                                    <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Plat Nomor -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</label>
                                <input
                                    required
                                    type="text"
                                    name="nomor_plat"
                                    value="{{ old('action_type') === 'create' ? old('nomor_plat') : '' }}"
                                    class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 uppercase @error('nomor_plat') border-red-500 @enderror"
                                    placeholder="BA 1234 XY"
                                />
                                @error('nomor_plat')
                                    <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Kapasitas -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas</label>
                                <input
                                    required
                                    type="number"
                                    name="kapasitas_mobil"
                                    min="1"
                                    max="20"
                                    value="{{ old('action_type') === 'create' ? old('kapasitas_mobil') : 5 }}"
                                    class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 @error('kapasitas_mobil') border-red-500 @enderror"
                                />
                                @error('kapasitas_mobil')
                                    <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Awal -->
                    <div class="md:col-span-2 space-y-2" x-data="{ status: 'aktif' }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Awal</label>
                        <input type="hidden" name="status_driver" :value="status">
                        <div class="grid grid-cols-2 gap-4">
                            <button
                                type="button"
                                @click="status = 'aktif'"
                                :class="status === 'aktif' ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'"
                                class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border"
                            >
                                Aktif (Tersedia)
                            </button>
                            <button
                                type="button"
                                @click="status = 'nonaktif'"
                                :class="status === 'nonaktif' ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'"
                                class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border"
                            >
                                Nonaktif (Tidak Aktif)
                            </button>
                        </div>
                    </div>
                </div>

                <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex gap-4">
                    <button 
                        type="button"
                        @click="isAddModalOpen = false"
                        class="flex-1 py-5 bg-white border border-slate-200 text-slate-600 rounded-3xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm active:scale-95"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 py-5 bg-blue-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95"
                    >
                        Simpan Data Driver
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Driver Modal -->
    @if($selectedDriver)
        <div 
            x-show="isEditModalOpen" 
            x-cloak
            class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
            x-transition:enter="transition-all duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all duration-300 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden"
                x-transition:enter="transition-all duration-300 ease-out"
                x-transition:enter-start="transform scale-95"
                x-transition:enter-end="transform scale-100"
                x-transition:leave="transition-all duration-300 ease-in"
                x-transition:leave-start="transform scale-100"
                x-transition:leave-end="transform scale-95"
                @click.outside="isEditModalOpen = false"
            >
                <form method="POST" action="{{ route('admin.drivers.update', $selectedDriver->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action_type" value="edit">

                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Edit Data Driver</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ubah profil dan kredensial driver</p>
                        </div>
                        <button 
                            type="button"
                            @click="isEditModalOpen = false" 
                            class="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-colors"
                        >
                            <!-- X Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8 max-h-[60vh] overflow-y-auto no-scrollbar">
                        
                        <!-- Nama Driver -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Driver</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input 
                                    required
                                    type="text" 
                                    name="nama_driver"
                                    value="{{ old('action_type') === 'edit' ? old('nama_driver') : $selectedDriver->nama_driver }}"
                                    class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('nama_driver') ring-2 ring-red-500 @enderror"
                                    placeholder="Nama Lengkap" 
                                />
                            </div>
                            @error('nama_driver')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nomor HP</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                                </svg>
                                <input 
                                    required
                                    type="tel" 
                                    name="no_hp"
                                    value="{{ old('action_type') === 'edit' ? old('no_hp') : $selectedDriver->no_hp }}"
                                    class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('no_hp') ring-2 ring-red-500 @enderror"
                                    placeholder="0812-xxxx-xxxx" 
                                />
                            </div>
                            @error('no_hp')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email (Login ID)</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                                </svg>
                                <input 
                                    required
                                    type="email" 
                                    name="email"
                                    value="{{ old('action_type') === 'edit' ? old('email') : $selectedDriver->user->email }}"
                                    class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('email') ring-2 ring-red-500 @enderror"
                                    placeholder="driver@singgalang.com" 
                                />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Baru (Opsional)</label>
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                                </svg>
                                <input 
                                    type="password" 
                                    name="password"
                                    class="w-full pl-11 pr-4 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-slate-900/5 transition-all @error('password') ring-2 ring-red-500 @enderror"
                                    placeholder="Kosongkan jika tidak diubah" 
                                />
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi Kendaraan -->
                        <div class="md:col-span-2 p-5 bg-blue-50/40 border border-blue-100 rounded-2xl space-y-4">
                            <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                                </svg>
                                Informasi Kendaraan Driver
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Nama Mobil -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Mobil</label>
                                    <input
                                        required
                                        type="text"
                                        name="nama_mobil"
                                        value="{{ old('action_type') === 'edit' ? old('nama_mobil') : $selectedDriver->nama_mobil }}"
                                        class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 @error('nama_mobil') border-red-500 @enderror"
                                    />
                                    @error('nama_mobil')
                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Plat Nomor -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</label>
                                    <input
                                        required
                                        type="text"
                                        name="nomor_plat"
                                        value="{{ old('action_type') === 'edit' ? old('nomor_plat') : $selectedDriver->nomor_plat }}"
                                        class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 uppercase @error('nomor_plat') border-red-500 @enderror"
                                    />
                                    @error('nomor_plat')
                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!-- Kapasitas -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas</label>
                                    <input
                                        required
                                        type="number"
                                        name="kapasitas_mobil"
                                        min="1"
                                        max="20"
                                        value="{{ old('action_type') === 'edit' ? old('kapasitas_mobil') : $selectedDriver->kapasitas_mobil }}"
                                        class="w-full px-4 py-3 bg-white border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-600/10 @error('kapasitas_mobil') border-red-500 @enderror"
                                    />
                                    @error('kapasitas_mobil')
                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status Driver -->
                        @php
                            $editStatus = old('action_type') === 'edit' ? old('status_driver') : $selectedDriver->status_driver;
                        @endphp
                        <div class="md:col-span-2 space-y-2" x-data="{ status: '{{ $editStatus }}' }">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Driver</label>
                            <input type="hidden" name="status_driver" :value="status">
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    type="button"
                                    @click="status = 'aktif'"
                                    :class="status === 'aktif' ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'"
                                    class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border"
                                >
                                    Aktif
                                </button>
                                <button
                                    type="button"
                                    @click="status = 'nonaktif'"
                                    :class="status === 'nonaktif' ? 'bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-900/20' : 'bg-white text-slate-400 border-slate-100 hover:bg-slate-50'"
                                    class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border"
                                >
                                    Nonaktif
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex gap-4">
                        <button 
                            type="button"
                            @click="isEditModalOpen = false"
                            class="flex-1 py-5 bg-white border border-slate-200 text-slate-600 rounded-3xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm active:scale-95"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 py-5 bg-blue-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-blue-600/20 hover:bg-blue-700 transition-all active:scale-95"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
