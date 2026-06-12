@extends('layouts.admin')

@section('content')
    <div class="space-y-8 font-poppins">
        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.3em] mb-2">Manajemen Operasional</p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Kelola Rute</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Atur asal, tujuan, dan tarif tetap untuk setiap rute perjalanan.</p>
            </div>
            <a href="{{ route('admin.rute.create') }}"
               class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-3.5 rounded-2xl text-[11px] font-bold uppercase tracking-widest shadow-sm flex items-center gap-2 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Rute
            </a>
        </div>

        <!-- Session Flash Notification -->
        <x-alert />

        <!-- Search & Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.rute.index') }}" class="flex flex-wrap gap-4 items-center">
                    <div class="relative flex-1 min-w-[280px]">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari kota asal atau tujuan..."
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.rute.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="bg-blue-800 rounded-2xl p-5 shadow-sm flex items-center justify-between text-white group">
                <div>
                    <p class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mb-1">Total Rute</p>
                    <h3 class="text-2xl font-bold text-white">{{ $rute->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table Card Container -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID Rute</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Asal & Tujuan</th>
                            <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tarif Tetap</th>
                            <th class="px-8 py-5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rute as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5 text-xs font-bold text-slate-900">
                                    RTE-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $item->asal }}</span>
                                            <div class="flex items-center gap-2 my-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-blue-600"></div>
                                                <div class="w-8 h-px bg-slate-200"></div>
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                            </div>
                                            <span class="text-sm font-bold text-slate-900">{{ $item->tujuan }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2 text-slate-900 text-sm font-bold">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941"></path>
                                        </svg>
                                        Rp {{ number_format($item->tarif, 0, ',', '.') }}
                                    </div>
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
                                <td colspan="4" class="px-8 py-16 text-center text-slate-400 font-medium">
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
                <div class="px-8 py-5 border-t border-slate-200 bg-slate-50/50">
                    {{ $rute->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
