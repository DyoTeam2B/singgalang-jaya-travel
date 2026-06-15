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
        $booking = Booking::with('pelanggan.user')->find($bookingId);

        if (!$booking) {
            session()->flash('error', 'Booking tidak ditemukan.');
            return;
        }

        $pelanggan = $booking->pelanggan;
        if (!$pelanggan || !$pelanggan->no_hp) {
            session()->flash('error', 'Nomor HP pelanggan tidak terdaftar.');
            return;
        }

        $target = $pelanggan->no_hp;
        $rute = $booking->jadwal->rute;
        $asal = $rute->asal;
        $tujuan = $rute->tujuan;
        $tanggal = \Carbon\Carbon::parse($booking->jadwal->tanggal_keberangkatan)->translatedFormat('d F Y');
        $jam = $booking->jadwal->jam_berangkat;

        $message = "Halo *{$pelanggan->nama}*,\n\nIni adalah konfirmasi untuk keberangkatan perjalanan Anda bersama *Singgalang Jaya Travel*:\n"
                 . "🎟️ Kode Booking: *{$booking->kode_booking}*\n"
                 . "🗺️ Rute: *{$asal} -> {$tujuan}*\n"
                 . "📅 Tanggal: *{$tanggal}*\n"
                 . "⏰ Jam Keberangkatan: *{$jam} WIB*\n"
                 . "👥 Penumpang: *{$booking->jumlah_penumpang} Orang*\n\n"
                 . "Status booking Anda saat ini telah dikonfirmasi. Harap bersiap di lokasi penjemputan 30 menit sebelum jadwal keberangkatan. Terima kasih.";

        $fonnteService = new FonnteService();
        $sent = $fonnteService->send(
            $target,
            $message,
            WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN,
            $booking->id
        );

        if ($sent) {
            session()->flash('success', "Konfirmasi WhatsApp berhasil dikirim ke {$pelanggan->nama} ({$target}).");
        } else {
            session()->flash('error', "Gagal mengirim konfirmasi WhatsApp ke {$target}. Silakan periksa log.");
        }
    }

    public function render()
    {
        $query = Booking::query()
            ->with(['pelanggan.user', 'jadwal.rute', 'pembayaran', 'whatsappNotifications' => function ($q) {
                $q->where('type', WhatsappNotification::TYPE_KONFIRMASI_KEBERANGKATAN)
                  ->where('status', WhatsappNotification::STATUS_SENT)
                  ->latest();
            }]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('kode_booking', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($qp) {
                      $qp->where('nama', 'like', '%' . $this->search . '%')
                         ->orWhere('no_hp', 'like', '%' . $this->search . '%')
                         ->orWhereHas('user', function($qu) {
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
}
