<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    /**
     * Override table name since it is singular in Indonesian
     */
    protected $table = 'pelanggan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'no_hp',
    ];

    /**
     * Get the user account for the pelanggan.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the bookings for the pelanggan.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pelanggan_id');
    }
}
