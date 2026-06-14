@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Manajemen Booking</h1>
            <p class="text-sm font-bold text-slate-500 mt-1 uppercase tracking-widest">
                Kelola manifes dan status pemesanan tiket penumpang
            </p>
        </div>
        
        <livewire:admin.booking-table />
    </div>
@endsection
