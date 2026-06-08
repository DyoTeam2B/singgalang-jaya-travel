<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    // Jenis Pembayaran Constants
    public const JENIS_DP = 'dp';
    public const JENIS_PELUNASAN = 'pelunasan';

    // Status Pembayaran Constants
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_TERVERIFIKASI = 'terverifikasi';
    public const STATUS_DITOLAK = 'ditolak';

    /**
     * Override table name since it is singular in Indonesian
     */
    protected $table = 'pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'jenis_pembayaran',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_bayar' => 'integer',
    ];

    /**
     * Local scope to filter only payments that are pending verification.
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status_pembayaran', self::STATUS_MENUNGGU);
    }

    /**
     * Local scope to filter only verified payments.
     */
    public function scopeTerverifikasi($query)
    {
        return $query->where('status_pembayaran', self::STATUS_TERVERIFIKASI);
    }

    /**
     * Helper to check if payment is verified.
     */
    public function isTerverifikasi(): bool
    {
        return $this->status_pembayaran === self::STATUS_TERVERIFIKASI;
    }

    /**
     * Get the booking that owns the payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
