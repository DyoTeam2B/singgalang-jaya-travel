@php
    $role = $user->role ?? 'pelanggan';
    $showPhoneField = in_array($role, ['pelanggan', 'driver'], true);
    $phoneValue = match ($role) {
        'driver' => $user->driver?->no_hp,
        'pelanggan' => $user->pelanggan?->no_hp,
        default => null,
    };
@endphp

<section>
    <div class="flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-blue-600">Data Akun</p>
            <h2 class="mt-2 text-xl font-black tracking-tight text-slate-950">Informasi Profil</h2>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-slate-500">
            {{ ucfirst($role) }}
        </span>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <div class="space-y-2">
                <label for="name" class="text-xs font-black uppercase tracking-widest text-slate-600">Nama Lengkap</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-bold text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="Nama lengkap">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <label for="email" class="text-xs font-black uppercase tracking-widest text-slate-600">Alamat Email</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                    </svg>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-bold text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="nama@email.com">
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-3 text-xs font-semibold text-amber-700">
                        Email belum terverifikasi.
                        <button form="send-verification" class="font-black underline underline-offset-4">Kirim ulang verifikasi</button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-black text-emerald-700">Link verifikasi baru sudah dikirim.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($showPhoneField)
            <div class="space-y-2">
                <label for="no_hp" class="text-xs font-black uppercase tracking-widest text-slate-600">Nomor Telepon / WhatsApp</label>
                <div class="relative max-w-xl">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.5 4.49a1 1 0 01-.5 1.21l-2.26 1.13a11.04 11.04 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.49 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"></path>
                    </svg>
                    <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp', $phoneValue) }}" required autocomplete="tel" class="h-12 w-full rounded-2xl border border-slate-200 bg-white pl-12 pr-4 text-sm font-bold text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10" placeholder="081234567890">
                </div>
                <p class="text-xs font-semibold text-slate-500">Nomor ini dipakai untuk notifikasi WhatsApp booking dan trip.</p>
                <x-input-error class="mt-2" :messages="$errors->get('no_hp')" />
            </div>
        @endif

        <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center">
            <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-blue-600/20 transition hover:bg-blue-700 active:scale-[0.99]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Profil
            </button>
        </div>
    </form>
</section>
