<?php

namespace App\Observers;

use App\Models\Trip;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class TripObserver
{
    /**
     * Handle the Trip "updated" event.
     */
    public function updated(Trip $trip): void
    {
        if ($trip->isDirty('status_trip')) {
            $newStatus = $trip->status_trip;
            $bookingStatus = null;

            switch ($newStatus) {
                case Trip::STATUS_ON_TRIP:
                    $bookingStatus = Booking::STATUS_ON_TRIP;
                    break;
                case Trip::STATUS_COMPLETED:
                    $bookingStatus = Booking::STATUS_COMPLETED;
                    break;
                case Trip::STATUS_CANCELLED:
                    $bookingStatus = Booking::STATUS_DIKONFIRMASI;
                    break;
                case Trip::STATUS_READY:
                case Trip::STATUS_NEW:
                    $bookingStatus = Booking::STATUS_ASSIGNED_TO_TRIP;
                    break;
            }

            if ($bookingStatus) {
                DB::transaction(function () use ($trip, $bookingStatus) {
                    $trip->loadMissing('detailTrips.booking');
                    foreach ($trip->detailTrips as $detail) {
                        if ($detail->booking) {
                            $detail->booking->update([
                                'status_booking' => $bookingStatus,
                            ]);
                        }
                    }
                });
            }
        }
    }

    /**
     * Handle the Trip "deleting" event.
     */
    public function deleting(Trip $trip): void
    {
        DB::transaction(function () use ($trip) {
            $trip->loadMissing('detailTrips.booking');
            foreach ($trip->detailTrips as $detail) {
                if ($detail->booking) {
                    $detail->booking->update([
                        'status_booking' => Booking::STATUS_DIKONFIRMASI,
                    ]);
                }
            }
        });
    }
}
