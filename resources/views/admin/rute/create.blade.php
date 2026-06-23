@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 font-poppins">
        <!-- Header Section -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.rute.index') }}"
               class="p-3 bg-white border border-slate-200 text-slate-450 hover:text-slate-600 rounded-2xl transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
            </a>
            <div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.3em] mb-1">Tambah Rute</p>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Rute Baru</h1>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('admin.rute.store') }}" class="p-8 sm:p-10 space-y-6">
                @csrf

                <!-- Kota Asal -->
                <div class="space-y-2">
                    <label for="asal" class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Kota Asal</label>
                    <input type="text" name="asal" id="asal" value="{{ old('asal') }}" required
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                           placeholder="Contoh: Padang Panjang">
                    @error('asal')
                        <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota Tujuan -->
                <div class="space-y-2">
                    <label for="tujuan" class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Kota Tujuan</label>
                    <input type="text" name="tujuan" id="tujuan" value="{{ old('tujuan') }}" required
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                           placeholder="Contoh: Pekanbaru">
                    @error('tujuan')
                        <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarif -->
                <div class="space-y-2">
                    <label for="tarif" class="text-[10px] font-black text-slate-400 uppercase tracking-widest block px-1">Tarif (Rp)</label>
                    <input type="number" name="tarif" id="tarif" value="{{ old('tarif') }}" required min="0"
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200/60 rounded-2xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all placeholder:text-slate-400"
                           placeholder="Contoh: 150000">
                    @error('tarif')
                        <p class="text-xs text-rose-500 font-bold mt-1 px-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex gap-4">
                    <a href="{{ route('admin.rute.index') }}"
                       class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-[0.98] shadow-lg shadow-blue-600/10">
                        Simpan Rute
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
