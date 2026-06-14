<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalPublicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal', [JadwalPublicController::class, 'index'])->name('jadwal.index');

Route::middleware('auth')->group(function () {
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
