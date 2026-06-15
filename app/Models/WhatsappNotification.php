<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
 class WhatsappNotification extends Model
 {
     use HasFactory;
 
     protected $table = 'whatsapp_notifications';
 
     // Type Constants
     public const TYPE_KONFIRMASI_KEBERANGKATAN = 'konfirmasi_keberangkatan';
     public const TYPE_PEMBATALAN_BOOKING = 'pembatalan_booking';
     public const TYPE_REMINDER_DP = 'reminder_dp';
     public const TYPE_CUSTOM = 'custom';
 
     // Status Constants
     public const STATUS_PENDING = 'pending';
     public const STATUS_SENT = 'sent';
     public const STATUS_FAILED = 'failed';
 
     protected $fillable = [
         'booking_id',
         'target',
         'message',
         'type',
         'status',
         'response',
     ];
 
     /**
      * Get the booking associated with the notification.
      */
     public function booking(): BelongsTo
     {
         return $this->belongsTo(Booking::class, 'booking_id');
     }
 }
