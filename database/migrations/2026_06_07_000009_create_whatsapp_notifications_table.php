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
         Schema::create('whatsapp_notifications', function (Blueprint $table) {
             $table->id();
             $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
             $table->string('target', 20);
             $table->text('message');
             $table->enum('type', ['konfirmasi_keberangkatan', 'pembatalan_booking', 'reminder_dp', 'custom']);
             $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
             $table->text('response')->nullable();
             $table->timestamps();
 
             // Indexes
             $table->index('booking_id');
             $table->index('type');
             $table->index('status');
         });
     }
 
     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
         Schema::dropIfExists('whatsapp_notifications');
     }
 };
