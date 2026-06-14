@extends('layouts.public')

@section('title', 'Cek Status Booking - Singgalang Jaya Travel')

@section('content')
<div class="py-16 md:py-24 bg-slate-50 flex-1 flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-6">
        @if(session('error'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-8 text-center border-b border-slate-100">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-800 mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Lacak Status Pemesanan</h2>
                <p class="text-sm text-slate-500">Masukkan kode booking unik Anda untuk melacak status pemesanan travel.</p>
            </div>

            <div class="p-8 space-y-5">
                {{-- Search Form --}}
                <form action="{{ route('cek-booking.show') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kode Booking</label>
                        <input type="text" name="kode_booking" value="{{ old('kode_booking', request('kode_booking')) }}" class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-mono font-bold uppercase placeholder-slate-400 transition-colors" placeholder="SJT-YYYYMMDD-XXXXX">
                        @error('kode_booking')
                            <p class="text-red-500 text-sm mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-3.5 px-4 rounded-xl transition-colors text-sm shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Lacak Pemesanan
                        </button>
                    </div>
                </form>

                <div class="text-center pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-400 font-medium">© 2026 Singgalang Jaya Travel • Aman & Nyaman</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
