@extends('layouts.admin')

@section('content')
    <div x-data="{
        scheduleId: '{{ old('jadwal_id', request('jadwal_id')) }}',
        driverId: '{{ old('driver_id') }}',
        schedules: {{ $schedules->map(fn ($schedule) => [
            'id' => $schedule->id,
            'date' => $schedule->tanggal_keberangkatan->format('d M Y'),
            'shift' => ucwords($schedule->shift),
            'time' => $schedule->jam_berangkat instanceof \DateTime ? $schedule->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i'),
            'route' => $schedule->rute->asal . ' -> ' . $schedule->rute->tujuan,
        ])->toJson() }},
        drivers: {{ $drivers->map(fn ($driver) => [
            'id' => $driver->id,
            'name' => $driver->nama_driver,
            'phone' => $driver->no_hp,
            'armada_name' => $driver->armada->nama_mobil ?? 'Belum ada armada',
            'armada_plate' => $driver->armada->nomor_plat ?? '-',
            'armada_capacity' => $driver->armada->kapasitas ?? '-',
        ])->toJson() }},
        get selectedSchedule() {
            return this.schedules.find((schedule) => schedule.id == this.scheduleId);
        },
        get selectedDriver() {
            return this.drivers.find((driver) => driver.id == this.driverId);
        }
    }" class="space-y-8 font-poppins max-w-4xl mx-auto py-6">

        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Operasional Trip</p>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Trip Baru</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Pilih jadwal dan driver. Armada otomatis mengikuti armada milik driver.</p>
            </div>
            <a href="{{ route('admin.trips.index') }}"
               class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-5 py-3 rounded-xl text-xs flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <x-alert />

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('admin.trips.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="jadwal_id" class="block text-sm font-medium text-slate-700">Jadwal Keberangkatan</label>
                        <span class="text-xs font-bold text-slate-400" x-text="schedules.length + ' jadwal aktif tersedia'"></span>
                    </div>
                    <select id="jadwal_id" name="jadwal_id" x-model="scheduleId"
                            class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Pilih Jadwal Aktif...</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ $schedule->tanggal_keberangkatan->format('d M Y') }} &middot; {{ ucwords($schedule->shift) }} ({{ $schedule->jam_berangkat instanceof \DateTime ? $schedule->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($schedule->jam_berangkat)->format('H:i') }}) &middot; {{ $schedule->rute->asal }} &rarr; {{ $schedule->rute->tujuan }}
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="driver_id" class="block text-sm font-medium text-slate-700">Driver</label>
                        <span class="text-xs font-bold text-slate-400" x-text="drivers.length + ' driver aktif tersedia'"></span>
                    </div>
                    <select id="driver_id" name="driver_id" x-model="driverId"
                            class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Pilih Driver Tersedia...</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->nama_driver }} &middot; {{ $driver->armada->nama_mobil ?? 'Tidak ada armada' }} &middot; {{ $driver->armada->nomor_plat ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('driver_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <template x-if="selectedSchedule">
                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl space-y-2 animate-in fade-in duration-300">
                        <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest">Detail Rencana Keberangkatan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold text-slate-600">
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Rute</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedSchedule.route"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Tanggal</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedSchedule.date"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Shift / Waktu</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedSchedule.shift + ' - ' + selectedSchedule.time"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="selectedDriver">
                    <div class="p-5 bg-emerald-50/50 border border-emerald-100 rounded-xl space-y-2 animate-in fade-in duration-300">
                        <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Detail Driver & Armada</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-bold text-slate-600">
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Nama Driver</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedDriver.name"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Kontak HP</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedDriver.phone"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Mobil / Tipe</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedDriver.armada_name"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Plat Nomor</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedDriver.armada_plate"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-slate-400 uppercase">Kapasitas Kursi</p>
                                <p class="text-slate-900 font-extrabold mt-0.5" x-text="selectedDriver.armada_capacity + ' Kursi'"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                    </svg>
                    <p class="text-xs font-semibold text-blue-800 leading-relaxed">
                        Armada otomatis mengikuti driver yang dipilih. Jika armada perlu diganti, ubah relasi armada pada data driver terlebih dahulu.
                    </p>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-100 justify-end">
                    <a href="{{ route('admin.trips.index') }}"
                       class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                        Buat Trip
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
