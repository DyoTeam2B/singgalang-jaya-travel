<?php

namespace App\Observers;

use App\Models\DetailTrip;
use App\Models\Booking;

class DetailTripObserver
{
    /**
     * Handle the DetailTrip "created" event.
     */
    public function created(DetailTrip $detailTrip): void
    {
        if ($detailTrip->booking) {
            $detailTrip->booking->update([
                'status_booking' => Booking::STATUS_ASSIGNED_TO_TRIP,
            ]);
        }
    }

    /**
     * Handle the DetailTrip "deleted" event.
     */
    public function deleted(DetailTrip $detailTrip): void
    {
        if ($detailTrip->booking) {
            $detailTrip->booking->update([
                'status_booking' => Booking::STATUS_DIKONFIRMASI,
            ]);
        }
    }
}
