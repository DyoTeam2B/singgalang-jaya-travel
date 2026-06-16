@extends('layouts.public')

@section('title', 'Profil Saya - Singgalang Jaya Travel')

@section('content')
    <section class="bg-slate-50 py-12 md:py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-col gap-3">
                <a href="{{ route('booking.index') }}" class="text-xs font-bold text-slate-500 hover:text-blue-700 uppercase tracking-widest transition-colors w-fit">
                    Booking Saya
                </a>
                <div>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Akun Pelanggan</p>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
                    <p class="text-sm font-semibold text-slate-500 mt-2">Kelola data login dan keamanan akun Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 md:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 md:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-white border border-red-100 shadow-sm rounded-2xl p-6 md:p-8">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection