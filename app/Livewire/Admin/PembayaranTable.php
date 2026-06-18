<?php

namespace App\Livewire\Admin;

use App\Models\Pembayaran;
use App\Models\Booking;
use App\Services\BookingWhatsappNotificationService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PembayaranTable extends Component
{
    public string $search = '';
    public string $statusFilter = 'Semua Status';
    public ?int $selectedPaymentId = null;
    public string $rejectReason = '';

    // Modal state controls
    public bool $isVerifyModalOpen = false;
    public bool $isRejectModalOpen = false;
    public bool $isPreviewOpen = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'Semua Status'],
        'selectedPaymentId' => ['as' => 'payment_id', 'except' => null],
    ];

    public function mount(): void
    {
        // Set first pending payment as default selected if none is provided
        if (!$this->selectedPaymentId) {
            $firstPending = Pembayaran::menunggu()->first();
            if ($firstPending) {
                $this->selectedPaymentId = $firstPending->id;
            }
        }
    }

    public function selectPayment($id): void
    {
        $this->selectedPaymentId = $id;
        $this->isVerifyModalOpen = false;
        $this->isRejectModalOpen = false;
        $this->rejectReason = '';
    }

    /**
     * Verify payment and update booking status to dikonfirmasi
     */
    public function verifyPayment(): void
    {
        if (!$this->selectedPaymentId) return;

        $pembayaran = Pembayaran::find($this->selectedPaymentId);
        if ($pembayaran) {
            $shouldSendNotification = $pembayaran->status_pembayaran !== Pembayaran::STATUS_TERVERIFIKASI;

            DB::transaction(function () use ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
                ]);

                $pembayaran->booking->update([
                    'status_booking' => Booking::STATUS_DIKONFIRMASI,
                ]);
            });

            $booking = $pembayaran->booking()
                ->with(['pelanggan', 'jadwal.rute'])
                ->first();

            if ($shouldSendNotification && $booking) {
                app(BookingWhatsappNotificationService::class)->sendDpVerifiedToCustomer($booking);
            }

            $this->isVerifyModalOpen = false;
            session()->flash('success', 'Pembayaran berhasil diverifikasi. Booking ' . $pembayaran->booking->kode_booking . ' status diperbarui menjadi Dikonfirmasi.');
        }
    }

    /**
     * Reject payment, save reason, and return booking to upload state
     */
    public function rejectPayment(): void
    {
        $this->validate([
            'rejectReason' => 'required|string|max:500',
        ], [
            'rejectReason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if (!$this->selectedPaymentId) return;

        $pembayaran = Pembayaran::find($this->selectedPaymentId);
        if ($pembayaran) {
            DB::transaction(function () use ($pembayaran) {
                $pembayaran->update([
                    'status_pembayaran' => Pembayaran::STATUS_DITOLAK,
                    'catatan' => $this->rejectReason,
                ]);

                $pembayaran->booking->update([
                    'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
                ]);
            });

            $this->isRejectModalOpen = false;
            $this->rejectReason = '';
            session()->flash('error', 'Bukti pembayaran ditolak. Status booking dikembalikan ke Booking Dibuat agar pelanggan dapat upload ulang.');
        }
    }

    public function render()
    {
        $query = Pembayaran::query()->with(['booking.pelanggan.user']);

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('booking', function($qb) {
                    $qb->where('kode_booking', 'like', '%' . $this->search . '%')
                       ->orWhereHas('pelanggan', function($qp) {
                           $qp->where('nama', 'like', '%' . $this->search . '%');
                       });
                });
            });
        }

        if ($this->statusFilter && $this->statusFilter !== 'Semua Status') {
            $query->where('status_pembayaran', $this->statusFilter);
        }

        $payments = $query->latest()->get();

        $selectedPayment = null;
        if ($this->selectedPaymentId) {
            $selectedPayment = Pembayaran::with(['booking.pelanggan.user'])->find($this->selectedPaymentId);
        }

        $pendingCount = Pembayaran::menunggu()->count();

        return view('livewire.admin.pembayaran-table', compact('payments', 'selectedPayment', 'pendingCount'));
    }
}
