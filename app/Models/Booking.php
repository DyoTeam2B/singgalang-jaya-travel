<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'pelanggan_id',
    'jadwal_id',
    'kode_booking',
    'alamat_jemput',
    'latitude_jemput',
    'longitude_jemput',
    'alamat_tujuan',
    'latitude_tujuan',
    'longitude_tujuan',
    'jumlah_penumpang',
    'total_harga',
    'status_booking',
])]
class Booking extends Model
{
    use HasFactory;

    /**
     * Get the pelanggan that owns the booking.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Get the jadwal associated with the booking.
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    /**
     * Get the pembayaran records for the booking.
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'booking_id');
    }

    /**
     * Get the detail trips for the booking.
     */
    public function detailTrips(): HasMany
    {
        return $this->hasMany(DetailTrip::class, 'booking_id');
    }
}
