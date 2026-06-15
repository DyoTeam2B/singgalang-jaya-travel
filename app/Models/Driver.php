<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'armada_id',
    'nama_driver',
    'no_hp',
    'status_driver',
])]
class Driver extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the driver.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the armada that the driver drives.
     */
    public function armada(): BelongsTo
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }

    /**
     * Get the trips for the driver.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    /**
     * Get the dynamic status of the driver based on DB status and active trips.
     */
    public function getDynamicStatusAttribute(): string
    {
        if ($this->status_driver === 'nonaktif') {
            return 'tidak_aktif';
        }

        $hasActiveTrip = $this->trips()
            ->whereIn('status_trip', ['ready', 'on_trip'])
            ->exists();

        return $hasActiveTrip ? 'sedang_bertugas' : 'tersedia';
    }
}
