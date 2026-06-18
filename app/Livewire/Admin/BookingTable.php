<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\WhatsappNotification;
use App\Services\FonnteService;
use Livewire\Component;
use Livewire\WithPagination;

class BookingTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'Semua Status';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'Semua Status'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Send WA Confirmation message via FonnteService.
     */
    public function sendWAConfirm($bookingId): void
    {
        $booking = Booking::with(['pelanggan.user', 'jadwal.rute'])->find($bookingId);

        if (! $booking) {
            session()->flash('error', 'Booking tidak ditemukan.');
            return;
        }

        $pelanggan = $booking->pelanggan;
        if (! $pelanggan || ! $pelanggan->no_hp) {
            session()->flash('error', 'Nomor HP pelanggan tidak terdaftar.');
            return;
        }

        $target = $pelanggan->no_hp;
        $message = $this->buildWAConfirmMessage($booking);

        $fonnteService = new FonnteService();
        $sent = $fonnteService->send(
            $target,
            $message,
            WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN,
            $booking->id
        );

        if ($sent) {
            session()->flash('success', "Permintaan WhatsApp masuk antrean Fonnte untuk {$pelanggan->nama} ({$target}). Cek status delivery di log Fonnte.");
        } else {
            session()->flash('error', "Gagal mengirim konfirmasi WhatsApp ke {$target}. Silakan periksa log.");
        }
    }

    public function render()
    {
        $query = Booking::query()
            ->with(['pelanggan.user', 'jadwal.rute', 'pembayaran', 'whatsappNotifications' => function ($q) {
                $q->where('type', WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN)
                    ->whereIn('status', [WhatsappNotification::STATUS_PENDING, WhatsappNotification::STATUS_SENT])
                    ->latest();
            }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('kode_booking', 'like', '%' . $this->search . '%')
                    ->orWhereHas('pelanggan', function ($qp) {
                        $qp->where('nama', 'like', '%' . $this->search . '%')
                            ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', function ($qu) {
                                $qu->where('email', 'like', '%' . $this->search . '%');
                            });
                    });
            });
        }

        if ($this->statusFilter && $this->statusFilter !== 'Semua Status') {
            $query->where('status_booking', $this->statusFilter);
        }

        $bookings = $query->latest()->paginate(10);

        return view('livewire.admin.booking-table', compact('bookings'));
    }

    private function buildWAConfirmMessage(Booking $booking): string
    {
        $pelanggan = $booking->pelanggan;
        $rute = $booking->jadwal->rute;
        $tanggal = $booking->jadwal->tanggal_keberangkatan?->format('d/m/Y') ?? '-';
        $shift = ucfirst($booking->jadwal->shift ?? '-');
        $jam = $booking->jadwal->jam_berangkat?->format('H:i') ?? '-';

        return implode("\n", [
            '*SINGGALANG JAYA TRAVEL*',
            '*KONFIRMASI KEBERANGKATAN*',
            '',
            "Halo {$pelanggan->nama},",
            '',
            'Berikut detail keberangkatan Anda:',
            '',
            '*Detail Booking*',
            "Kode      : {$booking->kode_booking}",
            "Rute      : {$rute->asal} -> {$rute->tujuan}",
            "Jadwal    : {$tanggal} - {$shift} {$jam} WIB",
            "Penumpang : {$booking->jumlah_penumpang} orang",
            'Status    : Dikonfirmasi',
            '',
            'Harap bersiap di lokasi penjemputan 30 menit sebelum jadwal keberangkatan.',
            '',
            'Terima kasih,',
            'Singgalang Jaya Travel',
        ]);
    }
}
