<?php
 
 namespace App\Services;
 
 use App\Models\WhatsappNotification;
 use Illuminate\Support\Facades\Http;
 use Illuminate\Support\Facades\Log;
 
 class FonnteService
 {
     /**
      * Send a WhatsApp message via FonnteAPI.
      *
      * @param string $target Target phone number (e.g. 081234567890)
      * @param string $message Message content
      * @param string $type Notification type (constants in WhatsappNotification)
      * @param int|null $bookingId Associated booking ID
      * @return bool True if message sent successfully
      */
     public function send(string $target, string $message, string $type, ?int $bookingId = null): bool
     {
         // 1. Create a pending notification record
         $notification = WhatsappNotification::create([
             'booking_id' => $bookingId,
             'target' => $target,
             'message' => $message,
             'type' => $type,
             'status' => WhatsappNotification::STATUS_PENDING,
         ]);
 
         $token = config('services.fonnte.token');
         $url = config('services.fonnte.url', 'https://api.fonnte.com/send');
 
         // Mock mode if token is empty
         if (empty($token)) {
             Log::info("Fonnte Service Mock: Sending WA message to {$target}. Message: {$message}");
             
             $notification->update([
                 'status' => WhatsappNotification::STATUS_SENT,
                 'response' => json_encode([
                     'status' => true,
                     'detail' => 'Mock mode: token is empty, logged as sent.',
                 ]),
             ]);
 
             return true;
         }
 
         try {
             // 2. Make the HTTP Request
             $response = Http::withHeaders([
                 'Authorization' => $token,
             ])->post($url, [
                 'target' => $target,
                 'message' => $message,
             ]);
 
             $body = $response->json();
             $responseBodyString = $response->body();
 
             // Fonnte returns {"status": true, ...} on success
             $isSuccess = isset($body['status']) && $body['status'] === true;
 
             $notification->update([
                 'status' => $isSuccess ? WhatsappNotification::STATUS_SENT : WhatsappNotification::STATUS_FAILED,
                 'response' => $responseBodyString,
             ]);
 
             return $isSuccess;
         } catch (\Exception $e) {
             Log::error("Fonnte Service Error sending to {$target}: " . $e->getMessage());
 
             $notification->update([
                 'status' => WhatsappNotification::STATUS_FAILED,
                 'response' => json_encode([
                     'status' => false,
                     'error' => $e->getMessage(),
                 ]),
             ]);
 
             return false;
         }
     }
 }
