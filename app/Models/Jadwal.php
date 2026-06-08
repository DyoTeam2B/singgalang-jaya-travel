<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    // Status Jadwal Constants
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
    public const STATUS_PENUH = 'penuh';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jadwal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rute_id',
        'tanggal_keberangkatan',
        'shift',
        'jam_berangkat',
        'kuota',
        'status_jadwal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'jam_berangkat' => 'datetime:H:i',
        'kuota' => 'integer',
    ];

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeAktif($query)
    {
        return $query->where('status_jadwal', self::STATUS_AKTIF);
    }

    /**
     * Get the route that this schedule belongs to.
     */
    public function rute(): BelongsTo
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    /**
     * Get the bookings for this schedule.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'jadwal_id');
    }

    /**
     * Get the trips for this schedule.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'jadwal_id');
    }
}
