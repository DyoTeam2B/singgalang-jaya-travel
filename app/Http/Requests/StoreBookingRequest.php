<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'pelanggan';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'jadwal_id' => ['required', 'exists:jadwal,id'],
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat_jemput' => ['required', 'string', 'max:500'],
            'latitude_jemput' => ['nullable', 'numeric'],
            'longitude_jemput' => ['nullable', 'numeric'],
            'alamat_tujuan' => ['required', 'string', 'max:500'],
            'latitude_tujuan' => ['nullable', 'numeric'],
            'longitude_tujuan' => ['nullable', 'numeric'],
            'jumlah_penumpang' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jadwal_id.required' => 'Jadwal keberangkatan wajib dipilih.',
            'jadwal_id.exists' => 'Jadwal keberangkatan tidak valid.',
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
        ];
    }

    /**
     * Add post-validation checks for available seats.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $jadwalId = $this->input('jadwal_id');
            $jumlah = $this->input('jumlah_penumpang');

            if ($jadwalId && $jumlah) {
                $jadwal = Jadwal::find($jadwalId);
                if ($jadwal) {
                    $bookedSeats = $jadwal->bookings()
                        ->whereNotIn('status_booking', [
                            Booking::STATUS_CANCELLED,
                            Booking::STATUS_EXPIRED
                        ])
                        ->sum('jumlah_penumpang');
                    
                    $available = $jadwal->kuota - $bookedSeats;

                    if ($jumlah > $available) {
                        $validator->errors()->add('jumlah_penumpang', "Jumlah penumpang melebihi kuota kursi yang tersedia ({$available} kursi tersisa).");
                    }
                }
            }

            // Check for duplicate active booking on the same schedule
            $user = auth()->user();
            $pelanggan = $user ? $user->pelanggan : null;
            if ($pelanggan && $jadwalId) {
                $hasActiveBooking = Booking::where('pelanggan_id', $pelanggan->id)
                    ->where('jadwal_id', $jadwalId)
                    ->whereNotIn('status_booking', [
                        Booking::STATUS_CANCELLED,
                        Booking::STATUS_COMPLETED,
                        Booking::STATUS_EXPIRED
                    ])
                    ->whereDoesntHave('pembayaran', function ($q) {
                        $q->where('status_pembayaran', \App\Models\Pembayaran::STATUS_DITOLAK);
                    })
                    ->exists();

                if ($hasActiveBooking) {
                    $validator->errors()->add('jadwal_id', 'Anda sudah memiliki booking aktif pada jadwal ini.');
                }
            }
        });
    }
}
