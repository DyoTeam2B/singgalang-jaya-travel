@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.jadwal.index') }}"
               class="p-3 bg-white border border-slate-200 text-slate-400 hover:text-slate-600 rounded-2xl transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
            </a>
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-1">Tambah Jadwal</p>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Jadwal Baru</h1>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
            <form method="POST" action="{{ route('admin.jadwal.store') }}" class="p-8 sm:p-10 space-y-6">
                @csrf

                <!-- Rute -->
                <div class="space-y-2">
                    <label for="rute_id" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Rute Perjalanan</label>
                    <select name="rute_id" id="rute_id" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer">
                        <option value="">Pilih Rute Perjalanan...</option>
                        @foreach($rute as $r)
                            <option value="{{ $r->id }}" {{ old('rute_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->asal }} → {{ $r->tujuan }} (Rp {{ number_format($r->tarif, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('rute_id')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Keberangkatan -->
                <div class="space-y-2">
                    <label for="tanggal_keberangkatan" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Tanggal Keberangkatan</label>
                    <input type="date" name="tanggal_keberangkatan" id="tanggal_keberangkatan" value="{{ old('tanggal_keberangkatan', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    @error('tanggal_keberangkatan')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shift & Jam Berangkat -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="shift" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Shift</label>
                        <select name="shift" id="shift" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer">
                            <option value="pagi" {{ old('shift') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                            <option value="malam" {{ old('shift') == 'malam' ? 'selected' : '' }}>Malam</option>
                        </select>
                        @error('shift')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="jam_berangkat" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Jam Keberangkatan</label>
                        <input type="time" name="jam_berangkat" id="jam_berangkat" value="{{ old('jam_berangkat', '08:00') }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        @error('jam_berangkat')
                            <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kuota (Kapasitas Penumpang) -->
                <div class="space-y-2">
                    <label for="kuota" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Kapasitas (Kuota Penumpang)</label>
                    <input type="number" name="kuota" id="kuota" value="{{ old('kuota', 5) }}" required min="1" max="50"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400"
                           placeholder="Contoh: 5">
                    @error('kuota')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Jadwal -->
                <div class="space-y-2">
                    <label for="status_jadwal" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Status Awal</label>
                    <select name="status_jadwal" id="status_jadwal" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer">
                        <option value="aktif" {{ old('status_jadwal') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status_jadwal') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status_jadwal')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex gap-4">
                    <a href="{{ route('admin.jadwal.index') }}"
                       class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 transition-all active:scale-98">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
