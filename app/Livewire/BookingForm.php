<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Services\BookingService;
use Livewire\Component;

class BookingForm extends Component
{
    // Form fields
    public $schedules;
    public $selectedJadwalId;
    public $nama;
    public $no_hp;
    public $alamat_jemput;
    public $latitude_jemput;
    public $longitude_jemput;
    public $alamat_tujuan;
    public $latitude_tujuan;
    public $longitude_tujuan;
    public $jumlah_penumpang = 1;

    // Computed properties
    public $tarif_per_orang = 0;
    public $total_harga = 0;
    public $available_seats = 0;

    /**
     * Component mount lifecycle.
     */
    public function mount($schedules, $preselectedJadwalId = null)
    {
        $this->schedules = $schedules;
        $this->nama = auth()->user()->name;
        $this->no_hp = auth()->user()->pelanggan?->no_hp ?? '';

        if ($preselectedJadwalId) {
            $this->selectedJadwalId = $preselectedJadwalId;
            $this->updatedSelectedJadwalId($preselectedJadwalId);
        }
    }

    /**
     * Triggered when selectedJadwalId changes.
     */
    public function updatedSelectedJadwalId($value)
    {
        if ($value) {
            $jadwal = Jadwal::with('rute')->find($value);
            if ($jadwal) {
                $this->tarif_per_orang = $jadwal->rute->tarif;
                
                // Calculate available seats
                $bookedSeats = $jadwal->bookings()
                    ->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED])
                    ->sum('jumlah_penumpang');
                
                $this->available_seats = max(0, $jadwal->kuota - $bookedSeats);
            } else {
                $this->resetCalculations();
            }
        } else {
            $this->resetCalculations();
        }
        $this->calculateTotal();
    }

    /**
     * Triggered when jumlah_penumpang changes.
     */
    public function updatedJumlahPenumpang()
    {
        $this->calculateTotal();
    }

    /**
     * Recalculate total price.
     */
    private function calculateTotal()
    {
        if ($this->selectedJadwalId && $this->jumlah_penumpang) {
            $this->total_harga = $this->tarif_per_orang * $this->jumlah_penumpang;
        } else {
            $this->total_harga = 0;
        }
    }

    /**
     * Reset calculated fields.
     */
    private function resetCalculations()
    {
        $this->tarif_per_orang = 0;
        $this->available_seats = 0;
        $this->total_harga = 0;
    }

    /**
     * Submit the booking.
     */
    public function submit(BookingService $bookingService)
    {
        $this->validate([
            'selectedJadwalId' => ['required', 'exists:jadwal,id'],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat_jemput' => ['required', 'string', 'max:500'],
            'latitude_jemput' => ['nullable', 'numeric'],
            'longitude_jemput' => ['nullable', 'numeric'],
            'alamat_tujuan' => ['required', 'string', 'max:500'],
            'latitude_tujuan' => ['nullable', 'numeric'],
            'longitude_tujuan' => ['nullable', 'numeric'],
            'jumlah_penumpang' => ['required', 'integer', 'min:1'],
        ], [
            'selectedJadwalId.required' => 'Jadwal keberangkatan wajib dipilih.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            'no_hp.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'no_hp.max' => 'Nomor HP/WhatsApp maksimal 20 karakter.',
            'alamat_jemput.required' => 'Alamat penjemputan wajib diisi.',
            'alamat_jemput.max' => 'Alamat penjemputan maksimal 500 karakter.',
            'alamat_tujuan.required' => 'Alamat tujuan wajib diisi.',
            'alamat_tujuan.max' => 'Alamat tujuan maksimal 500 karakter.',
            'jumlah_penumpang.required' => 'Jumlah penumpang wajib diisi.',
            'jumlah_penumpang.integer' => 'Jumlah penumpang harus berupa angka.',
            'jumlah_penumpang.min' => 'Jumlah penumpang minimal 1 orang.',
        ]);

        // Double check seat kuota
        $jadwal = Jadwal::find($this->selectedJadwalId);
        if ($jadwal) {
            $bookedSeats = $jadwal->bookings()
                ->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED])
                ->sum('jumlah_penumpang');
            
            $available = $jadwal->kuota - $bookedSeats;

            if ($this->jumlah_penumpang > $available) {
                $this->addError('jumlah_penumpang', "Jumlah penumpang melebihi kuota kursi yang tersedia ({$available} kursi tersisa).");
                return;
            }
        }

        $data = [
            'jadwal_id' => $this->selectedJadwalId,
            'nama' => $this->nama,
            'no_hp' => $this->no_hp,
            'alamat_jemput' => $this->alamat_jemput,
            'latitude_jemput' => $this->latitude_jemput,
            'longitude_jemput' => $this->longitude_jemput,
            'alamat_tujuan' => $this->alamat_tujuan,
            'latitude_tujuan' => $this->latitude_tujuan,
            'longitude_tujuan' => $this->longitude_tujuan,
            'jumlah_penumpang' => $this->jumlah_penumpang,
        ];

        $booking = $bookingService->createBooking($data, auth()->user());

        session()->flash('success', 'Booking berhasil dibuat. Silakan tinjau pesanan Anda.');

        return redirect()->route('booking.review', ['kode' => $booking->kode_booking]);
    }

    /**
     * Render the Livewire component view.
     */
    public function render()
    {
        return view('livewire.booking-form');
    }
}
