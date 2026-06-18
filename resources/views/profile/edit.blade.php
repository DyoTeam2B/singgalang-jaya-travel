<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-black tracking-tight text-slate-900">Profil Saya</h2>
    </x-slot>

    <div class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @include('profile.partials.profile-page-content')
        </div>
    </div>
</x-app-layout>
