@extends('layouts.admin')

@section('content')
<div class="font-poppins pb-8 space-y-8">

    {{-- Flash Messages --}}
    @if(session('info'))
        <x-alert type="info" :message="session('info')" />
    @endif

    {{-- Top Header & Filters --}}
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-[0.3em]">Financial Report</p>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-1">Laporan Keuangan</h1>
            <p class="text-sm font-medium text-slate-500">Analisis pendapatan dan performa armada</p>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-center gap-3" id="filter-form">
            {{-- Shift Filter --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <select name="shift" onchange="document.getElementById('filter-form').submit()"
                    class="pl-11 pr-8 py-3 bg-white border border-slate-200 shadow-sm rounded-xl text-sm font-semibold text-slate-700 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer min-w-[160px] transition-colors">
                    <option value="semua" {{ $shift === 'semua' ? 'selected' : '' }}>Semua Shift</option>
                    <option value="pagi" {{ $shift === 'pagi' ? 'selected' : '' }}>Shift Pagi</option>
                    <option value="malam" {{ $shift === 'malam' ? 'selected' : '' }}>Shift Malam</option>
                </select>
            </div>

            {{-- Period Filter --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <select name="period" onchange="document.getElementById('filter-form').submit()"
                    class="pl-11 pr-8 py-3 bg-white border border-slate-200 shadow-sm rounded-xl text-sm font-semibold text-slate-700 appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer min-w-[180px] transition-colors">
                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
            </div>

            {{-- Export Button --}}
            <div x-data="{ open: false }">
                <button type="button" @click="open = true"
                    class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>

                {{-- Export Modal --}}
                <template x-teleport="body">
                    <div x-show="open" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0">
                        <div @click.outside="open = false"
                            class="bg-white w-full max-w-md rounded-2xl shadow-lg overflow-hidden p-8 text-center"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mx-auto mb-5">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-2">Export Laporan CSV</h3>
                            <p class="text-sm text-slate-500 mb-6 px-4 leading-relaxed">Pilih rentang laporan yang ingin Anda unduh ke format CSV yang bisa dibuka di Excel.</p>

                            <form action="{{ route('admin.laporan.export') }}" method="GET" class="space-y-4 mb-6 text-left">
                                <input type="hidden" name="shift" value="{{ $shift }}">
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider px-1">Rentang Waktu</label>
                                    <div class="relative">
                                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <select name="export_period"
                                            class="w-full pl-11 pr-8 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 appearance-none focus:ring-2 focus:ring-blue-500 cursor-pointer transition-colors">
                                            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari Ini</option>
                                            <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                                            <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>Bulan Ini</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="p-3.5 bg-blue-50 rounded-xl border border-blue-100 flex items-center gap-3">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-blue-700">Sertakan Rincian Pendapatan Driver</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 pt-2">
                                    <button type="button" @click="open = false"
                                        class="py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-xl text-sm transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="py-3 bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded-xl text-sm shadow-sm transition-colors inline-flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export CSV
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        {{-- Total Booking --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Booking</p>
                <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalBookings) }} <span class="text-xs font-medium text-slate-400">Pax {{ number_format($totalPassengers) }}</span></h3>
            </div>
        </div>

        {{-- Pendapatan --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pendapatan Bersih</p>
                <h3 class="text-3xl font-bold text-slate-900 tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Total Trip --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Trip</p>
                <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ number_format($totalTrips) }} <span class="text-xs font-medium text-slate-400">Keberangkatan</span></h3>
            </div>
        </div>

        {{-- Okupansi --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Okupansi Rata-rata</p>
                <h3 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $avgOccupancy }}% <span class="text-xs font-medium text-slate-400">Kapasitas</span></h3>
            </div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-1">Grafik Pendapatan</h3>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Analisis berdasarkan periode operasional</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    Pendapatan
                </span>
            </div>
        </div>

        <div class="h-72 md:h-80 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Daily Report Table --}}
    <div x-data="{ selectedReport: null }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="text-lg font-bold text-slate-900">Rincian Laporan Harian</h3>
            <span class="inline-flex text-xs font-semibold text-blue-700 bg-blue-50 px-4 py-1.5 rounded-full border border-blue-100">{{ $periodLabel }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Booking & Trip</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pendapatan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Cancelled</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dailyReports as $index => $report)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d M Y') }}</p>
                                        <p class="text-[10px] font-medium text-slate-400">{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('l') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-bold text-slate-900">{{ $report->total_booking }}</p>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase">Booking</p>
                                    </div>
                                    <div class="w-px h-6 bg-slate-200"></div>
                                    <div class="flex flex-col">
                                        <p class="text-sm font-bold text-slate-900">{{ $report->total_trip }}</p>
                                        <p class="text-[10px] font-semibold text-slate-400 uppercase">Trip</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-bold text-slate-900">Rp {{ number_format($report->revenue, 0, ',', '.') }}</p>
                                <p class="text-[10px] font-semibold text-emerald-600 uppercase">Net Revenue</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($report->cancelled > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        {{ $report->cancelled }}
                                    </span>
                                @else
                                    <span class="text-sm text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button
                                    @click="selectedReport = {
                                        date: '{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('d M Y') }}',
                                        day: '{{ \Carbon\Carbon::parse($report->report_date)->translatedFormat('l') }}',
                                        booking: {{ $report->total_booking }},
                                        trip: {{ $report->total_trip }},
                                        passengers: {{ $report->total_passengers ?? 0 }},
                                        revenue: {{ $report->revenue }},
                                        dpRevenue: {{ $report->dp_revenue }},
                                        pelunasanRevenue: {{ $report->pelunasan_revenue }},
                                        cancelled: {{ $report->cancelled }}
                                    }"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-400">Belum ada data laporan untuk periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Detail Report Modal --}}
        <template x-teleport="body">
            <div x-show="selectedReport !== null" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div @click.outside="selectedReport = null"
                    class="bg-white w-full max-w-4xl rounded-2xl shadow-lg overflow-hidden flex flex-col max-h-[90vh]"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    {{-- Modal Header --}}
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 tracking-tight">Rincian Laporan Operasional</h3>
                            <p class="text-xs font-medium text-slate-400 mt-0.5" x-text="selectedReport?.date + ' • ' + selectedReport?.day"></p>
                        </div>
                        <button @click="selectedReport = null" class="p-2 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="flex-1 overflow-y-auto p-6 space-y-6">
                        {{-- Financial Summary Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                                <p class="text-lg font-bold text-slate-900" x-text="'Rp ' + (selectedReport?.revenue || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="p-5 bg-blue-50 rounded-2xl border border-blue-100">
                                <p class="text-[10px] font-semibold text-blue-400 uppercase tracking-wider mb-2">DP Collected</p>
                                <p class="text-lg font-bold text-blue-600" x-text="'Rp ' + (selectedReport?.dpRevenue || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <p class="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider mb-2">Pelunasan Collected</p>
                                <p class="text-lg font-bold text-emerald-600" x-text="'Rp ' + (selectedReport?.pelunasanRevenue || 0).toLocaleString('id-ID')"></p>
                            </div>
                            <div class="p-5 bg-amber-50 rounded-2xl border border-amber-100">
                                <p class="text-[10px] font-semibold text-amber-400 uppercase tracking-wider mb-2">Sisa Pelunasan</p>
                                <p class="text-lg font-bold text-amber-600" x-text="'Rp ' + Math.max(0, (selectedReport?.revenue || 0) - (selectedReport?.dpRevenue || 0) - (selectedReport?.pelunasanRevenue || 0)).toLocaleString('id-ID')"></p>
                            </div>
                        </div>

                        {{-- Stats Row --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="flex items-center gap-4 p-5 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase">Booking</p>
                                    <p class="text-lg font-bold text-slate-900" x-text="selectedReport?.booking || 0"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase">Penumpang</p>
                                    <p class="text-lg font-bold text-slate-900" x-text="selectedReport?.passengers || 0"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 p-5 bg-white border border-slate-100 rounded-2xl shadow-sm">
                                <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-rose-400 uppercase">Dibatalkan</p>
                                    <p class="text-lg font-bold text-rose-600" x-text="selectedReport?.cancelled || 0"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Trip Summary Table --}}
                        @if($tripSummary->count() > 0)
                        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                            <div class="p-5 border-b border-slate-100 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Rangkuman Trip Periode</h4>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-100">
                                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Trip & Rute</th>
                                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Driver & Armada</th>
                                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Pax</th>
                                            <th class="px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($tripSummary as $trip)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-5 py-3">
                                                    <p class="text-sm font-bold text-slate-900">Trip #{{ $trip->id }}</p>
                                                    <p class="text-[10px] font-medium text-slate-400">
                                                        @if($trip->jadwal && $trip->jadwal->rute)
                                                            {{ $trip->jadwal->rute->asal }} → {{ $trip->jadwal->rute->tujuan }}
                                                            ({{ ucfirst($trip->jadwal->shift) }})
                                                        @else
                                                            —
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="px-5 py-3">
                                                    <p class="text-sm font-bold text-slate-900">{{ $trip->driver->nama_driver ?? '-' }}</p>
                                                    <p class="text-[10px] font-medium text-slate-400 uppercase">
                                                        @if($trip->armada)
                                                            {{ $trip->armada->nama_mobil }} ({{ $trip->armada->nomor_plat }})
                                                        @else
                                                            —
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="px-5 py-3 text-center">
                                                    <span class="text-sm font-bold text-slate-900">{{ $trip->detailTrips->count() }}</span>
                                                </td>
                                                <td class="px-5 py-3 text-center">
                                                    <x-status-badge status="{{ $trip->status_trip }}" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Modal Footer --}}
                    <div class="p-6 border-t border-slate-100 flex justify-end gap-3 bg-slate-50/50 shrink-0">
                        <button @click="selectedReport = null"
                            class="px-6 py-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-xl text-sm transition-colors">
                            Tutup
                        </button>
                        <a href="{{ route('admin.laporan.export', ['period' => $period, 'shift' => $shift]) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded-xl text-sm shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Unduh CSV
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const labels = @json($chartLabels);
    const values = @json($chartValues);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: values,
                borderColor: '#2563eb',
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx: c, chartArea} = chart;
                    if (!chartArea) return 'rgba(37, 99, 235, 0.1)';
                    const gradient = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
                    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');
                    return gradient;
                },
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#94a3b8',
                    titleFont: { size: 11, weight: 'bold' },
                    bodyColor: '#1e3a5f',
                    bodyFont: { size: 13, weight: 'bold' },
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11, weight: '600' },
                        padding: 10,
                    },
                },
                y: {
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false,
                    },
                    border: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11, weight: '600' },
                        padding: 10,
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + ' Jt';
                            if (value >= 1000) return (value / 1000) + ' Rb';
                            return value;
                        }
                    },
                    beginAtZero: true,
                }
            }
        }
    });
});
</script>
@endsection
