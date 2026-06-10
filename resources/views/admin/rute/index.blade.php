@extends('layouts.admin')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Manajemen Rute</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Rute Perjalanan</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Kelola daftar kota asal, tujuan, dan tarif tiket perjalanan.</p>
            </div>
            <a href="{{ route('admin.rute.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-blue-600/20 flex items-center gap-2 transition-all active:scale-95">
                <!-- Plus Icon SVG -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Rute Baru
            </a>
        </div>

        <!-- Session Flash Notification -->
        <x-alert />

        <!-- Card Container -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <!-- Search & Toolbar -->
            <div class="p-6 border-b border-slate-100 bg-white">
                <form method="GET" action="{{ route('admin.rute.index') }}" class="flex flex-wrap gap-4 items-center">
                    <div class="relative flex-1 min-w-[280px]">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari kota asal atau tujuan..."
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.rute.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kota Asal</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kota Tujuan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tarif</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($rute as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5 text-sm font-bold text-slate-900">
                                    {{ $rute->firstItem() + $index }}
                                </td>
                                <td class="px-8 py-5 text-sm font-semibold text-slate-700">
                                    {{ $item->asal }}
                                </td>
                                <td class="px-8 py-5 text-sm font-semibold text-slate-700">
                                    {{ $item->tujuan }}
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-blue-600">
                                    Rp {{ number_format($item->tarif, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <!-- Edit button -->
                                        <a href="{{ route('admin.rute.edit', $item->id) }}"
                                           class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-colors shadow-sm"
                                           title="Edit Rute">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                            </svg>
                                        </a>

                                        <!-- Delete button (form) -->
                                        <form action="{{ route('admin.rute.destroy', $item->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus rute {{ $item->asal }} - {{ $item->tujuan }}?');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-rose-600 hover:border-rose-200 transition-colors shadow-sm"
                                                    title="Hapus Rute">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-medium">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                                    </svg>
                                    Tidak ada rute perjalanan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($rute->hasPages())
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50">
                    {{ $rute->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
