<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    // Status Trip Constants
    public const STATUS_NEW = 'new';
    public const STATUS_READY = 'ready';
    public const STATUS_ON_TRIP = 'on_trip';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'jadwal_id',
        'driver_id',
        'armada_id',
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
     * Get the armada associated with the trip.
     */
    public function armada(): BelongsTo
    {
        return $this->belongsTo(Armada::class);
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

    protected static function booted()
    {
        static::updated(function ($trip) {
            if ($trip->isDirty('status_trip')) {
                if ($trip->status_trip === self::STATUS_ON_TRIP) {
                    $trip->loadMissing(['driver', 'jadwal.rute']);
                    \App\Models\ActivityLog::create([
                        'title' => 'Trip Dimulai',
                        'description' => 'Driver ' . ($trip->driver->nama_driver ?? 'N/A') . ' memulai perjalanan Rute: ' . ($trip->jadwal->rute->asal ?? '') . ' -> ' . ($trip->jadwal->rute->tujuan ?? '') . ' (TRP-' . str_pad($trip->id, 3, '0', STR_PAD_LEFT) . ')',
                        'type' => 'trip_started',
                    ]);
                } elseif ($trip->status_trip === self::STATUS_COMPLETED) {
                    $trip->loadMissing('driver');
                    \App\Models\ActivityLog::create([
                        'title' => 'Trip Selesai',
                        'description' => 'Driver ' . ($trip->driver->nama_driver ?? 'N/A') . ' telah menyelesaikan perjalanan TRP-' . str_pad($trip->id, 3, '0', STR_PAD_LEFT),
                        'type' => 'trip_completed',
                    ]);
                }
            }
        });
    }
}
