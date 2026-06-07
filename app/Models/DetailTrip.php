<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTrip extends Model
{
    protected $table = 'detail_trip';

    protected $fillable = [
        'trip_id',
        'booking_id',
        'status_jemput',
        'status_antar',
        'picked_up_at',
        'dropped_off_at',
    ];

    protected $casts = [
        'picked_up_at' => 'datetime',
        'dropped_off_at' => 'datetime',
    ];

    /**
     * Get the trip that owns the detail.
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the booking associated with the trip detail.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
