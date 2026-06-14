<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalPublicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal', [JadwalPublicController::class, 'index'])->name('jadwal.index');
Route::get('/cek-booking', [\App\Http\Controllers\CekBookingController::class, 'index'])->name('cek-booking.index');
Route::post('/cek-booking', [\App\Http\Controllers\CekBookingController::class, 'show'])->name('cek-booking.show');
Route::get('/jadwal/available', [JadwalPublicController::class, 'available'])->name('jadwal.available');
Route::get('/jadwal/{id}/check-kuota', [JadwalPublicController::class, 'checkKuota'])->name('jadwal.checkKuota');

Route::middleware('auth')->group(function () {
    // Pelanggan Routes
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/booking/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
        Route::get('/booking/{kode}/review', [\App\Http\Controllers\BookingController::class, 'review'])->name('booking.review');
        Route::get('/booking/{kode}/pembayaran', [\App\Http\Controllers\PembayaranController::class, 'show'])->name('booking.pembayaran');
        Route::post('/booking/{kode}/pembayaran', [\App\Http\Controllers\PembayaranController::class, 'store'])->name('booking.pembayaran.store');
        Route::get('/booking/{kode}/edit', [\App\Http\Controllers\BookingController::class, 'edit'])->name('booking.edit');
        Route::put('/booking/{kode}', [\App\Http\Controllers\BookingController::class, 'update'])->name('booking.update');
        Route::put('/booking/{kode}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('booking.cancel');
    });

    // Redirection Dashboard (Breeze Default)
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('driver.dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('role:admin')
         ->prefix('admin')
         ->name('admin.')
         ->group(function () {
             Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
             Route::resource('rute', \App\Http\Controllers\Admin\RuteController::class);
             Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
             Route::put('jadwal/{jadwal}/toggle', [\App\Http\Controllers\Admin\JadwalController::class, 'toggleStatus'])->name('jadwal.toggle');
             Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
             Route::post('trips/{trip}/assign', [\App\Http\Controllers\Admin\TripController::class, 'assignBooking'])->name('trips.assign');
             Route::delete('trips/{trip}/remove/{detailTrip}', [\App\Http\Controllers\Admin\TripController::class, 'removeBooking'])->name('trips.remove');
             Route::resource('trips', \App\Http\Controllers\Admin\TripController::class);

             // Admin Bookings & Pembayaran (Sprint 2 - Nayasha)
             Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show']);
             Route::put('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
             Route::resource('pembayaran', \App\Http\Controllers\Admin\PembayaranController::class)->only(['index', 'show']);
             Route::put('pembayaran/{pembayaran}/verify', [\App\Http\Controllers\Admin\PembayaranController::class, 'verify'])->name('pembayaran.verify');
             Route::put('pembayaran/{pembayaran}/reject', [\App\Http\Controllers\Admin\PembayaranController::class, 'reject'])->name('pembayaran.reject');
         });

    // Driver Routes
    Route::middleware('role:driver')
         ->prefix('driver')
         ->name('driver.')
         ->group(function () {
             Route::get('/dashboard', function () {
                 return view('driver.dashboard');
             })->name('dashboard');
         });
});


require __DIR__.'/auth.php';
