@extends('layouts.public')

@section('title', 'Profil Saya - Singgalang Jaya Travel')

@section('content')
    <div class="bg-slate-50 py-10 md:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('profile.partials.profile-page-content')
        </div>
    </div>
@endsection
