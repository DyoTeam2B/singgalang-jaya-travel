<?php

use App\Models\Booking;
use App\Models\WhatsappNotification;
use App\Services\FonnteService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Symfony\Component\Console\Command\Command;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('booking:send-confirmation {--dry-run : Preview pesan tanpa mengirim WhatsApp}', function () {
    $operationalTimezone = 'Asia/Jakarta';
    $appTimezone = config('app.timezone', 'UTC');
    $today = now($operationalTimezone)->startOfDay();
    $notificationWindowStart = $today->copy()->timezone($appTimezone);
    $notificationWindowEnd = $today->copy()->endOfDay()->timezone($appTimezone);
    $fonnteService = app(FonnteService::class);

    $bookings = Booking::with(['pelanggan', 'jadwal.rute', 'detailTrips.trip.driver'])
        ->whereIn('status_booking', [
            Booking::STATUS_DIKONFIRMASI,
            Booking::STATUS_ASSIGNED_TO_TRIP,
        ])
        ->whereHas('jadwal', function ($query) use ($today) {
            $query->whereDate('tanggal_keberangkatan', $today->toDateString());
        })
        ->whereDoesntHave('whatsappNotifications', function ($query) use ($notificationWindowStart, $notificationWindowEnd) {
            $query->where('type', WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN)
                ->whereBetween('created_at', [$notificationWindowStart, $notificationWindowEnd])
                ->whereIn('status', [
                    WhatsappNotification::STATUS_PENDING,
                    WhatsappNotification::STATUS_SENT,
                ]);
        })
        ->get();

    $sent = 0;
    $failed = 0;
    $dryRun = (bool) $this->option('dry-run');

    foreach ($bookings as $booking) {
        $jadwal = $booking->jadwal;
        $rute = $jadwal?->rute;
        $detailTrip = $booking->detailTrips->first();
        $driver = $detailTrip?->trip?->driver;
        $namaPelanggan = $booking->pelanggan?->nama ?? 'Pelanggan';
        $target = $booking->pelanggan?->no_hp ?? '';
        $tanggal = $jadwal?->tanggal_keberangkatan?->translatedFormat('d F Y') ?? $today->translatedFormat('d F Y');
        $jam = $jadwal?->jam_berangkat?->format('H:i') ?? '-';
        $namaDriver = $driver?->nama_driver ?? '-';
        $noHpDriver = $driver?->no_hp ? " ({$driver->no_hp})" : '';

        $message = implode("\n", [
            "Halo {$namaPelanggan}, ini konfirmasi keberangkatan Singgalang Jaya Travel hari ini.",
            '',
            "Kode Booking: {$booking->kode_booking}",
            'Rute: '.($rute ? "{$rute->asal} -> {$rute->tujuan}" : '-'),
            "Jadwal: {$tanggal} pukul {$jam} WIB",
            "Titik Jemput: {$booking->alamat_jemput}",
            "Titik Antar: {$booking->alamat_tujuan}",
            "Driver: {$namaDriver}{$noHpDriver}",
            '',
            'Mohon siap di titik jemput sebelum jadwal keberangkatan. Terima kasih.',
        ]);

        if ($dryRun) {
            $this->line("[DRY RUN] {$booking->kode_booking} -> ".($target ?: 'nomor kosong'));
            continue;
        }

        if ($fonnteService->send($target, $message, WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN, $booking->id)) {
            $sent++;
        } else {
            $failed++;
        }
    }

    if ($dryRun) {
        $this->info("Dry run selesai. {$bookings->count()} booking siap dikirim untuk {$today->toDateString()}.");

        return Command::SUCCESS;
    }

    $this->info("Konfirmasi keberangkatan selesai. Terkirim: {$sent}, gagal: {$failed}, total: {$bookings->count()}.");

    return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
})->purpose('Kirim WhatsApp konfirmasi pagi untuk booking yang berangkat hari ini');

Schedule::command('booking:send-confirmation')->dailyAt('06:00')->timezone('Asia/Jakarta');