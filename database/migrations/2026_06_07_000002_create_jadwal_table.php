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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->onDelete('cascade');
            $table->date('tanggal_keberangkatan');
            $table->enum('shift', ['pagi', 'malam']);
            $table->time('jam_berangkat');
            $table->unsignedInteger('kuota');
            $table->enum('status_jadwal', ['aktif', 'nonaktif', 'penuh'])->default('aktif');
            $table->timestamps();

            // Indexes
            $table->index(['rute_id', 'tanggal_keberangkatan', 'shift']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
