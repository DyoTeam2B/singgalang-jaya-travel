@extends('layouts.public')

@section('title', 'Pemesanan Travel - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center mb-10 overflow-x-auto pb-4">
            <div class="flex items-center gap-2 sm:gap-4 text-xs font-semibold uppercase tracking-wider min-w-max">
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">1</span>
                    <span>Pemesanan</span>
                </div>
                <div class="w-10 h-0.5 bg-slate-200"></div>
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 text-xs">2</span>
                    <span>Review</span>
                </div>
                <div class="w-10 h-0.5 bg-slate-200"></div>
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 text-xs">3</span>
                    <span>Pembayaran</span>
                </div>
            </div>
        </div>

        {{-- Header Section --}}
        <div class="text-center max-w-2xl mx-auto mb-10">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">Layanan Travel Singgalang Jaya</p>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight mb-3">Pemesanan Tiket</h1>
            <p class="text-slate-500 text-sm">Data diri Anda diambil otomatis dari profil akun. Lengkapi data perjalanan untuk memesan travel dengan layanan antar-jemput <em>door-to-door</em>.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 max-w-3xl mx-auto">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 max-w-3xl mx-auto">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Livewire Component --}}
        <livewire:booking-form :schedules="$schedules" :preselectedJadwalId="$preselectedJadwalId" />
    </div>
</div>
@endsection
