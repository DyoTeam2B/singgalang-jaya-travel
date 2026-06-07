<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan');
            $table->unsignedBigInteger('jadwal_id'); // Defer constraint until Rayfo creates jadwal table
            $table->string('kode_booking', 20)->unique();
            $table->string('alamat_jemput', 500);
            $table->decimal('latitude_jemput', 10, 8)->nullable();
            $table->decimal('longitude_jemput', 11, 8)->nullable();
            $table->string('alamat_tujuan', 500);
            $table->decimal('latitude_tujuan', 10, 8)->nullable();
            $table->decimal('longitude_tujuan', 11, 8)->nullable();
            $table->unsignedInteger('jumlah_penumpang')->default(1);
            $table->unsignedInteger('total_harga');
            $table->enum('status_booking', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dikonfirmasi',
                'masuk_trip',
                'dalam_perjalanan',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_pembayaran');
            $table->timestamps();

            // Indexes
            $table->index('status_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
