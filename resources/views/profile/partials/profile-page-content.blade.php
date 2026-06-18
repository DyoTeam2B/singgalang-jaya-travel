@php
    $role = $user->role ?? 'pelanggan';
    $roleLabel = match ($role) {
        'admin' => 'Admin',
        'driver' => 'Driver',
        default => 'Pelanggan',
    };
    $roleDescription = match ($role) {
        'admin' => 'Akun operasional panel admin',
        'driver' => 'Akun operasional driver',
        default => 'Akun pemesanan pelanggan',
    };
    $phoneNumber = match ($role) {
        'driver' => $user->driver?->no_hp,
        'pelanggan' => $user->pelanggan?->no_hp,
        default => null,
    };
    $initial = strtoupper(mb_substr($user->name ?? 'U', 0, 1));
    $backUrl = match ($role) {
        'admin' => route('admin.dashboard'),
        'driver' => route('driver.dashboard'),
        default => route('booking.index'),
    };
    $backLabel = match ($role) {
        'admin' => 'Dashboard Admin',
        'driver' => 'Dashboard Driver',
        default => 'Booking Saya',
    };
@endphp

<section class="space-y-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div class="space-y-3">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.24em] text-slate-500 hover:text-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ $backLabel }}
            </a>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600">Profil {{ $roleLabel }}</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950 md:text-4xl">Profil Saya</h1>
                <p class="mt-2 max-w-2xl text-sm font-semibold leading-relaxed text-slate-500">Kelola identitas akun, nomor WhatsApp, dan keamanan login.</p>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="inline-flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-black text-emerald-700 shadow-sm">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                Profil berhasil diperbarui.
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
        <aside class="overflow-hidden rounded-3xl bg-slate-950 text-white shadow-xl shadow-slate-900/10">
            <div class="border-b border-white/10 p-7">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-black shadow-xl shadow-blue-600/20">
                        {{ $initial }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-lg font-black leading-tight">{{ $user->name }}</p>
                        <p class="mt-1 text-[10px] font-black uppercase tracking-[0.24em] text-blue-300">{{ $roleDescription }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 p-7 text-sm">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Email</p>
                    <p class="mt-1 break-words font-bold text-slate-100">{{ $user->email }}</p>
                </div>

                @if ($phoneNumber)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Nomor WhatsApp</p>
                        <p class="mt-1 font-bold text-slate-100">{{ $phoneNumber }}</p>
                    </div>
                @endif

                @if ($role === 'driver')
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-slate-400">Armada</p>
                        <p class="mt-1 font-bold text-slate-100">{{ $user->driver?->armada?->nama_mobil ?? 'Belum ditautkan' }}</p>
                        @if ($user->driver?->armada)
                            <p class="mt-1 text-xs font-semibold text-slate-400">{{ $user->driver->armada->nomor_plat }}</p>
                        @endif
                    </div>
                @endif

                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-4">
                    <p class="text-[10px] font-black uppercase tracking-[0.24em] text-emerald-300">Status Akun</p>
                    <p class="mt-1 font-bold text-emerald-100">Aktif sebagai {{ $roleLabel }}</p>
                </div>
            </div>
        </aside>

        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div id="password" class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-3xl border border-rose-100 bg-white p-5 shadow-sm sm:p-7">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</section>
