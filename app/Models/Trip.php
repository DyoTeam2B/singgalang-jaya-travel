<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    protected $fillable = [
        'jadwal_id',
        'driver_id',
        'status_trip',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the driver associated with the trip.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the schedule associated with the trip.
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    /**
     * Get the passenger/booking details associated with the trip.
     */
    public function detailTrips(): HasMany
    {
        return $this->hasMany(DetailTrip::class);
    }
}
