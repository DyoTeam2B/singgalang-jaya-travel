@extends('layouts.admin')

@section('content')
    <div x-data="{ 
        isAddModalOpen: {{ $errors->any() && old('action_type') === 'create' ? 'true' : 'false' }}, 
        isEditModalOpen: {{ $errors->any() && old('action_type') === 'edit' ? 'true' : 'false' }} 
    }" class="space-y-8 font-poppins min-h-screen py-6 px-4">

        <!-- Top Header -->
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Operasional Travel</p>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Manajemen Armada</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Kelola data kendaraan dan status operasional armada travel.</p>
            </div>
            
            <button 
                @click="isAddModalOpen = true"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3.5 rounded-2xl transition-all shadow-lg shadow-blue-600/10 hover:shadow-xl hover:shadow-blue-600/15 active:scale-[0.98] text-[10px] font-black uppercase tracking-wider"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Armada
            </button>
        </div>

        <!-- Session Alerts -->
        <x-alert />

        <!-- Toolbar (Search & Filter) -->
        <div class="mb-8 flex flex-col xl:flex-row xl:items-center gap-4">
            <form method="GET" action="{{ route('admin.armada.index') }}" class="w-full flex flex-col xl:flex-row xl:items-center gap-4">
                <div class="relative w-full xl:w-[400px] shrink-0">
                    <!-- Search Icon -->
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Cari Plat Nomor atau Nama Mobil..." 
                        value="{{ $search }}"
                        class="w-full pl-11 pr-4 py-4 bg-white border border-slate-200 rounded-[1.5rem] text-xs font-bold focus:ring-4 focus:ring-blue-600/10 outline-none transition-all shadow-sm"
                    />
                </div>

                <div class="relative w-full xl:w-64">
                    <select 
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full px-4 py-4 bg-white border border-slate-200 shadow-sm rounded-[1.5rem] text-xs font-bold text-slate-900 focus:ring-4 focus:ring-blue-600/10 cursor-pointer outline-none"
                    >
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ $statusFilter === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $statusFilter === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                @if($search || $statusFilter)
                    <a href="{{ route('admin.armada.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition-colors py-4 px-2">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <!-- Data Layout (Table & Detail Column) -->
        <div class="flex flex-col xl:flex-row gap-8 items-start min-h-[500px]">
            
            <!-- Left Column: Data Table -->
            <div class="flex-1 w-full bg-white rounded-[2rem] border border-slate-200/80 shadow-sm flex flex-col overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Armada</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Plat Nomor</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver Terkait</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($armadas as $armada)
                                @php
                                    $isSelected = $selectedArmada && $selectedArmada->id === $armada->id;
                                @endphp
                                <tr 
                                    onclick="window.location.href='{{ route('admin.armada.index', ['selected_id' => $armada->id, 'search' => $search, 'status' => $statusFilter, 'page' => $armadas->currentPage()]) }}'"
                                    class="transition-all cursor-pointer group {{ $isSelected ? 'bg-slate-50' : 'hover:bg-slate-50/50' }}"
                                >
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-slate-100 shadow-sm flex items-center justify-center text-blue-600">
                                                <!-- Car SVG icon -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-900">{{ $armada->nama_mobil }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">ARM-{{ str_pad($armada->id, 3, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-xs font-black text-slate-700 uppercase">
                                        {{ $armada->nomor_plat }}
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-xs font-bold text-slate-600">
                                        {{ $armada->kapasitas }} Kursi
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-xs text-slate-600">
                                        @if($armada->driver)
                                            <span class="font-bold text-slate-900">{{ $armada->driver->nama_driver }}</span>
                                        @else
                                            <span class="text-slate-400 italic">Belum ditautkan</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <x-status-badge status="{{ $armada->status_armada }}" />
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right" onclick="event.stopPropagation()">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <!-- Edit Trigger -->
                                            <button 
                                                @click="isEditModalOpen = true"
                                                class="p-2.5 bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-blue-600 transition-colors shadow-sm active:scale-95"
                                                title="Edit Armada"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-16 text-center text-slate-400 font-bold">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                                        </svg>
                                        Tidak ada data armada ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($armadas->hasPages())
                    <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
                        {{ $armadas->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Column: Detail Panel -->
            @if($selectedArmada)
                @php
                    $activeTripsCount = $selectedArmada->trips()->whereIn('status_trip', ['ready', 'on_trip'])->count();
                @endphp
                <div class="w-full xl:w-[400px] bg-white rounded-[2rem] border border-slate-200/80 shadow-sm flex flex-col overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Detail Armada</h3>
                        <span class="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                            ARM-{{ str_pad($selectedArmada->id, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <div class="flex-1 p-8 space-y-8">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-28 h-28 rounded-[2.5rem] p-1 border border-slate-100 shadow-inner mb-6 flex items-center justify-center bg-blue-50 border-blue-100 text-blue-600">
                                <svg class="w-14 h-14" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 leading-tight mb-2 uppercase">{{ $selectedArmada->nomor_plat }}</h3>
                            <p class="text-xs text-slate-500 font-bold mb-4">{{ $selectedArmada->nama_mobil }}</p>
                            <x-status-badge status="{{ $selectedArmada->status_armada }}" />
                        </div>

                        <div class="space-y-6">
                            <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kapasitas Kursi</span>
                                    <span class="text-xs font-black text-slate-900">{{ $selectedArmada->kapasitas }} Penumpang</span>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Driver Terkait</span>
                                    <span class="text-xs font-black text-slate-900">
                                        {{ $selectedArmada->driver ? $selectedArmada->driver->nama_driver : 'Tidak ada' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Trips</span>
                                    <span class="text-xs font-black text-slate-900">{{ $activeTripsCount }} Trip</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50/50 border-t border-slate-100 grid grid-cols-2 gap-4">
                        <button 
                            @click="isEditModalOpen = true"
                            class="py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 shadow-sm"
                        >
                            Edit Armada
                        </button>
                        
                        <form action="{{ route('admin.armada.destroy', $selectedArmada->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/10 transition-all active:scale-95"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data armada {{ $selectedArmada->nama_mobil }} ({{ $selectedArmada->nomor_plat }})?');"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="w-full xl:w-[400px] h-[500px] flex flex-col items-center justify-center text-center p-12 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-100 opacity-40">
                    <svg class="w-16 h-16 text-slate-300 mb-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124l-.318-5.085a1.875 1.875 0 00-1.875-1.758h-11.5c-.955 0-1.782.686-1.875 1.635l-.178 1.82M12 9.75V3m0 0L8.25 6.75M12 3l3.75 3.75m-9.375 9h15.75"></path>
                    </svg>
                    <p class="text-sm font-black text-slate-500 uppercase tracking-[0.2em]">Pilih Armada untuk Detail</p>
                </div>
            @endif
        </div>

        <!-- Add Armada Modal -->
        <div 
            x-show="isAddModalOpen" 
            x-cloak
            class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
        >
            <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden" @click.outside="isAddModalOpen = false">
                <form method="POST" action="{{ route('admin.armada.store') }}">
                    @csrf
                    <input type="hidden" name="action_type" value="create">

                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Armada Baru</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Masukkan detail spesifikasi kendaraan baru</p>
                        </div>
                        <button type="button" @click="isAddModalOpen = false" class="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-10 space-y-6 max-h-[60vh] overflow-y-auto">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Mobil / Tipe</label>
                            <input 
                                required
                                type="text" 
                                name="nama_mobil"
                                value="{{ old('action_type') === 'create' ? old('nama_mobil') : '' }}"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                                placeholder="Contoh: Toyota Avanza, Suzuki Ertiga" 
                            />
                            @error('nama_mobil')
                                <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Plat Nomor</label>
                            <input 
                                required
                                type="text" 
                                name="nomor_plat"
                                value="{{ old('action_type') === 'create' ? old('nomor_plat') : '' }}"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold uppercase text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                                placeholder="Contoh: BA 1234 XY" 
                            />
                            @error('nomor_plat')
                                <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kapasitas Penumpang</label>
                            <input 
                                required
                                type="number" 
                                name="kapasitas"
                                min="1"
                                max="20"
                                value="{{ old('action_type') === 'create' ? old('kapasitas') : 5 }}"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all"
                            />
                            @error('kapasitas')
                                <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2" x-data="{ status: 'aktif' }">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Status Armada</label>
                            <input type="hidden" name="status_armada" :value="status">
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    type="button"
                                    @click="status = 'aktif'"
                                    :class="status === 'aktif' ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/10' : 'bg-white text-slate-400 border-slate-200/60 hover:bg-slate-50'"
                                    class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border active:scale-95"
                                >
                                    Aktif
                                </button>
                                <button
                                    type="button"
                                    @click="status = 'nonaktif'"
                                    :class="status === 'nonaktif' ? 'bg-slate-900 text-white border-slate-900 shadow-lg shadow-slate-900/10' : 'bg-white text-slate-400 border-slate-200/60 hover:bg-slate-50'"
                                    class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border active:scale-95"
                                >
                                    Nonaktif
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex gap-4">
                        <button type="button" @click="isAddModalOpen = false" class="flex-1 py-5 bg-white border border-slate-200/60 text-slate-600 rounded-3xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 active:scale-95 shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-5 bg-blue-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/10 hover:bg-blue-700 active:scale-95">
                            Simpan Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Armada Modal -->
        @if($selectedArmada)
            <div 
                x-show="isEditModalOpen" 
                x-cloak
                class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md"
            >
                <div class="bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden" @click.outside="isEditModalOpen = false">
                    <form method="POST" action="{{ route('admin.armada.update', $selectedArmada->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action_type" value="edit">

                        <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Edit Armada</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ubah data detail spesifikasi kendaraan</p>
                            </div>
                            <button type="button" @click="isEditModalOpen = false" class="p-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="p-10 space-y-6 max-h-[60vh] overflow-y-auto">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Mobil / Tipe</label>
                                <input 
                                    required
                                    type="text" 
                                    name="nama_mobil"
                                    value="{{ old('action_type') === 'edit' ? old('nama_mobil') : $selectedArmada->nama_mobil }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                                    placeholder="Toyota Avanza" 
                                />
                                @error('nama_mobil')
                                    <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Plat Nomor</label>
                                <input 
                                    required
                                    type="text" 
                                    name="nomor_plat"
                                    value="{{ old('action_type') === 'edit' ? old('nomor_plat') : $selectedArmada->nomor_plat }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold uppercase text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                                    placeholder="BA 1234 XY" 
                                />
                                @error('nomor_plat')
                                    <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kapasitas Penumpang</label>
                                <input 
                                    required
                                    type="number" 
                                    name="kapasitas"
                                    min="1"
                                    max="20"
                                    value="{{ old('action_type') === 'edit' ? old('kapasitas') : $selectedArmada->kapasitas }}"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all"
                                />
                                @error('kapasitas')
                                    <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                                @enderror
                            </div>

                             <div class="space-y-2" x-data="{ status: '{{ old('action_type') === 'edit' ? old('status_armada') : $selectedArmada->status_armada }}' }">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Status Armada</label>
                                <input type="hidden" name="status_armada" :value="status">
                                <div class="grid grid-cols-2 gap-4">
                                    <button
                                        type="button"
                                        @click="status = 'aktif'"
                                        :class="status === 'aktif' ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/10' : 'bg-white text-slate-400 border-slate-200/60 hover:bg-slate-50'"
                                        class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border active:scale-95"
                                    >
                                        Aktif
                                    </button>
                                    <button
                                        type="button"
                                        @click="status = 'nonaktif'"
                                        :class="status === 'nonaktif' ? 'bg-slate-900 text-white border-slate-900 shadow-lg shadow-slate-900/10' : 'bg-white text-slate-400 border-slate-200/60 hover:bg-slate-50'"
                                        class="py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border active:scale-95"
                                    >
                                        Nonaktif
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex gap-4">
                            <button type="button" @click="isEditModalOpen = false" class="flex-1 py-5 bg-white border border-slate-200/60 text-slate-600 rounded-3xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 active:scale-95 shadow-sm">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-5 bg-blue-600 text-white rounded-3xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/10 hover:bg-blue-700 active:scale-95">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
@endsection
