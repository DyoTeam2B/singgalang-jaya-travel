<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'jenis_pembayaran',
    'jumlah_bayar',
    'metode_pembayaran',
    'bukti_pembayaran',
    'status_pembayaran',
    'catatan',
])]
class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Override table name since it is singular in Indonesian
     */
    protected $table = 'pembayaran';

    /**
     * Get the booking that owns the payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
