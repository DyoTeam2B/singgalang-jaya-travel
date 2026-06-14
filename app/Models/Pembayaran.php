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

    // Payment amount and voucher constants
    public const NOMINAL_DP = 50000;
    public const VOUCHER_LUNAS_10 = 'LUNAS10';
    public const DISKON_LUNAS_PERSEN = 10;

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
        'voucher_kode',
        'diskon_persen',
        'nominal_diskon',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_bayar' => 'integer',
        'diskon_persen' => 'integer',
        'nominal_diskon' => 'integer',
    ];

    /**
     * Calculate the 10% voucher discount for full payment.
     */
    public static function hitungDiskonLunas(int $totalHarga): int
    {
        return intdiv($totalHarga * self::DISKON_LUNAS_PERSEN, 100);
    }

    /**
     * Calculate amount paid when customer chooses full payment with voucher.
     */
    public static function hitungNominalLunas(int $totalHarga): int
    {
        return max(0, $totalHarga - self::hitungDiskonLunas($totalHarga));
    }

    /**
     * Helper to check if payment is a down payment.
     */
    public function isDp(): bool
    {
        return $this->jenis_pembayaran === self::JENIS_DP;
    }

    /**
     * Helper to check if payment is full payment.
     */
    public function isPelunasan(): bool
    {
        return $this->jenis_pembayaran === self::JENIS_PELUNASAN;
    }

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
