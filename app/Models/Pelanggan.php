<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'no_hp'])]
class Pelanggan extends Model
{
    use HasFactory;

    /**
     * Override table name since it is singular in Indonesian
     */
    protected $table = 'pelanggan';

    /**
     * Get the bookings for the pelanggan.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'pelanggan_id');
    }
}
