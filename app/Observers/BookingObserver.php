<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "saved" event.
     */
    public function saved(Booking $booking): void
    {
        if ($booking->jadwal) {
            $booking->jadwal->checkAndUpdateStatus();
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        if ($booking->jadwal) {
            $booking->jadwal->checkAndUpdateStatus();
        }
    }
}
