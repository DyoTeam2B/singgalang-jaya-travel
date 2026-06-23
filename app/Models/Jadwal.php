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
        return $query->where('status_jadwal', self::STATUS_AKTIF)
            ->where(function ($q) {
                $q->where('tanggal_keberangkatan', '>', now()->toDateString())
                  ->orWhere(function ($q2) {
                      $q2->where('tanggal_keberangkatan', '=', now()->toDateString())
                         ->where('jam_berangkat', '>', now()->toTimeString());
                  });
            });
    }

    /**
     * Check if the schedule is expired (departure time in the past).
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->tanggal_keberangkatan || !$this->jam_berangkat) {
            return false;
        }

        $departureDate = $this->tanggal_keberangkatan->toDateString();
        $departureTime = $this->jam_berangkat instanceof \DateTime 
            ? $this->jam_berangkat->format('H:i:s') 
            : \Carbon\Carbon::parse($this->jam_berangkat)->toTimeString();

        $now = now();
        $today = $now->toDateString();

        if ($departureDate < $today) {
            return true;
        }

        if ($departureDate === $today && $departureTime <= $now->toTimeString()) {
            return true;
        }

        return false;
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

    /**
     * Recalculate and update status_jadwal based on booked seats.
     */
    public function checkAndUpdateStatus(): void
    {
        $hasActiveTrip = $this->trips()
            ->whereIn('status_trip', [Trip::STATUS_ON_TRIP, Trip::STATUS_COMPLETED])
            ->exists();

        if ($hasActiveTrip) {
            $this->update(['status_jadwal' => self::STATUS_NONAKTIF]);
            return;
        }

        $bookedSeats = $this->bookings()
            ->whereNotIn('status_booking', [
                Booking::STATUS_CANCELLED,
                Booking::STATUS_EXPIRED
            ])
            ->sum('jumlah_penumpang');

        if ($bookedSeats >= $this->kuota) {
            $this->update(['status_jadwal' => self::STATUS_PENUH]);
        } else {
            $this->update(['status_jadwal' => self::STATUS_AKTIF]);
        }
    }
}
