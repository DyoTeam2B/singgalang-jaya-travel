@extends('layouts.admin')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.rute.index') }}"
               class="p-3 bg-white border border-slate-200 text-slate-400 hover:text-slate-600 rounded-2xl transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
            </a>
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-1">Ubah Rute</p>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Edit Rute</h1>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden">
            <form method="POST" action="{{ route('admin.rute.update', $rute->id) }}" class="p-8 sm:p-10 space-y-6">
                @csrf
                @method('PUT')

                <!-- Kota Asal -->
                <div class="space-y-2">
                    <label for="asal" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Kota Asal</label>
                    <div class="relative">
                        <input type="text" name="asal" id="asal" value="{{ old('asal', $rute->asal) }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400"
                               placeholder="Contoh: Padang Panjang">
                    </div>
                    @error('asal')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota Tujuan -->
                <div class="space-y-2">
                    <label for="tujuan" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Kota Tujuan</label>
                    <div class="relative">
                        <input type="text" name="tujuan" id="tujuan" value="{{ old('tujuan', $rute->tujuan) }}" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400"
                               placeholder="Contoh: Pekanbaru">
                    </div>
                    @error('tujuan')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarif -->
                <div class="space-y-2">
                    <label for="tarif" class="text-[10px] font-black text-slate-500 uppercase tracking-widest block">Tarif (Rp)</label>
                    <div class="relative">
                        <input type="number" name="tarif" id="tarif" value="{{ old('tarif', $rute->tarif) }}" required min="0"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder:text-slate-400"
                               placeholder="Contoh: 150000">
                    </div>
                    @error('tarif')
                        <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex gap-4">
                    <a href="{{ route('admin.rute.index') }}"
                       class="flex-1 py-4 bg-white border border-slate-200 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 transition-all active:scale-98">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
