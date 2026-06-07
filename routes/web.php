<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalPublicController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal', [JadwalPublicController::class, 'index'])->name('jadwal.index');

Route::middleware(['auth', 'role:admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function(){
        Route::get('/dashboard', function(){
            return view('admin.dashboard');
        })->name('dashboard');
     });
Route::middleware(['auth', 'role:driver'])
     ->prefix('driver')
     ->name('driver.')
     ->group(function(){
        Route::get('/dashboard', function(){
            return view('driver.dashboard');
        })->name('dashboard');
     });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
