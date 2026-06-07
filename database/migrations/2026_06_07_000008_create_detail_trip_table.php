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
        Schema::create('detail_trip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->enum('status_jemput', ['belum', 'sudah_dijemput'])->default('belum');
            $table->enum('status_antar', ['belum', 'sudah_diantar'])->default('belum');
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('dropped_off_at')->nullable();
            $table->timestamps();

            // Constraints
            $table->unique(['trip_id', 'booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_trip');
    }
};
