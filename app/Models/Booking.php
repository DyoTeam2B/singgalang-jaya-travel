<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_BOOKING_DIBUAT = 'booking_dibuat';
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    public const STATUS_DIKONFIRMASI = 'dikonfirmasi';
    public const STATUS_ASSIGNED_TO_TRIP = 'assigned_to_trip';
    public const STATUS_ON_TRIP = 'on_trip';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED = 'expired';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'alasan_pembatalan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude_jemput' => 'float',
        'longitude_jemput' => 'float',
        'latitude_tujuan' => 'float',
        'longitude_tujuan' => 'float',
        'jumlah_penumpang' => 'integer',
        'total_harga' => 'integer',
    ];

    /**
     * Local scope to filter only confirmed bookings.
     */
    public function scopeDikonfirmasi($query)
    {
        return $query->where('status_booking', self::STATUS_DIKONFIRMASI);
    }

    /**
     * Helper to check if booking is awaiting verification.
     */
    public function isMenungguVerifikasi(): bool
    {
        return $this->status_booking === self::STATUS_MENUNGGU_VERIFIKASI;
    }

    /**
     * Helper to check if booking is confirmed.
     */
    public function isDikonfirmasi(): bool
    {
        return $this->status_booking === self::STATUS_DIKONFIRMASI;
    }

    /**
     * Helper to check if booking is cancelled.
     */
    public function isDibatalkan(): bool
    {
        return $this->status_booking === self::STATUS_CANCELLED;
    }

    /**
     * Helper to check if booking is expired.
     */
    public function isExpired(): bool
    {
        return $this->status_booking === self::STATUS_EXPIRED;
    }

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

    /**
     * Get the whatsapp notification logs for the booking.
     */
    public function whatsappNotifications(): HasMany
    {
        return $this->hasMany(WhatsappNotification::class, 'booking_id');
    }
}