<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'booking_id',
        'pelanggan_id',
        'rating',
        'ulasan',
        'status',
    ];

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_HIDDEN = 'hidden';

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
