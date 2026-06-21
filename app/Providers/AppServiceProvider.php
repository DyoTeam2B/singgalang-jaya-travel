<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Trip::observe(\App\Observers\TripObserver::class);
        \App\Models\DetailTrip::observe(\App\Observers\DetailTripObserver::class);
    }
}
