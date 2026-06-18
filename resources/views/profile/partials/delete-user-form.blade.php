<section class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-rose-600">Zona Berisiko</p>
            <h2 class="mt-2 text-xl font-black tracking-tight text-slate-950">Hapus Akun</h2>
            <p class="mt-2 max-w-2xl text-sm font-semibold leading-relaxed text-slate-500">Akun yang dihapus tidak bisa dipakai kembali. Data yang terhubung ke akun juga akan ikut terdampak.</p>
        </div>

        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="inline-flex h-12 items-center justify-center gap-2 rounded-2xl border border-rose-200 bg-rose-50 px-5 text-xs font-black uppercase tracking-widest text-rose-700 transition hover:bg-rose-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.87 12.14A2 2 0 0116.14 21H7.86a2 2 0 01-1.99-1.86L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-8 0h10"></path>
            </svg>
            Hapus Akun
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight text-slate-950">Konfirmasi hapus akun</h2>
                    <p class="mt-2 text-sm font-semibold leading-relaxed text-slate-500">Masukkan password untuk memastikan akun ini benar-benar ingin dihapus.</p>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <label for="password" class="text-xs font-black uppercase tracking-widest text-slate-600">Password</label>
                <input id="password" name="password" type="password" class="h-12 w-full rounded-2xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-800 shadow-sm outline-none transition focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10" placeholder="Password akun">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <button type="button" x-on:click="$dispatch('close')" class="h-12 rounded-2xl border border-slate-200 bg-white text-xs font-black uppercase tracking-widest text-slate-600 transition hover:bg-slate-50">Batal</button>
                <button type="submit" class="h-12 rounded-2xl bg-rose-600 text-xs font-black uppercase tracking-widest text-white shadow-xl shadow-rose-600/20 transition hover:bg-rose-700">Hapus Akun</button>
            </div>
        </form>
    </x-modal>
</section>
