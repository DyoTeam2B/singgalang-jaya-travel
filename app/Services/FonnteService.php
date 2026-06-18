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
        $normalizedTarget = $this->normalizeTarget($target);

        // 1. Create a pending notification record
        $notification = WhatsappNotification::create([
            'booking_id' => $bookingId,
            'target' => $normalizedTarget ?: $target,
            'message' => $message,
            'type' => $type,
            'status' => WhatsappNotification::STATUS_PENDING,
        ]);

        if ($normalizedTarget === '') {
            $notification->update([
                'status' => WhatsappNotification::STATUS_FAILED,
                'response' => json_encode([
                    'status' => false,
                    'reason' => 'Nomor WhatsApp kosong atau tidak valid.',
                ]),
            ]);

            return false;
        }

        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $connectOnly = (bool) config('services.fonnte.connect_only', false);

        // Mock mode if token is empty
        if (empty($token)) {
            Log::info("Fonnte Service Mock: Sending WA message to {$normalizedTarget}. Message: {$message}");

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
            ])->asForm()->post($url, [
                'target' => $normalizedTarget,
                'message' => $message,
                'countryCode' => config('services.fonnte.country_code', '62'),
                'connectOnly' => $connectOnly ? 'true' : 'false',
            ]);

            $body = $response->json();
            $responseBodyString = $response->body();

            $isAccepted = is_array($body) && isset($body['status']) && $body['status'] === true;

            $notification->update([
                'status' => $this->resolveNotificationStatus(is_array($body) ? $body : []),
                'response' => $responseBodyString,
            ]);

            return $isAccepted;
        } catch (\Exception $e) {
            Log::error("Fonnte Service Error sending to {$normalizedTarget}: " . $e->getMessage());

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

    private function normalizeTarget(string $target): string
    {
        $target = trim($target);

        if ($target === '' || $target === '-') {
            return '';
        }

        $target = preg_replace('/[^0-9+]/', '', $target) ?? '';

        if (str_starts_with($target, '+62')) {
            return '62' . substr($target, 3);
        }

        if (str_starts_with($target, '0')) {
            return '62' . substr($target, 1);
        }

        return ltrim($target, '+');
    }

    /**
     * Fonnte `status: true` means the API accepted the request. Delivery can
     * still be queued, so keep local status pending until Fonnte reports sent.
     *
     * @param array<string, mixed> $body
     */
    private function resolveNotificationStatus(array $body): string
    {
        if (($body['status'] ?? false) !== true) {
            return WhatsappNotification::STATUS_FAILED;
        }

        $process = strtolower((string) ($body['process'] ?? ''));
        $detail = strtolower((string) ($body['detail'] ?? ''));

        if ($process === 'pending' || str_contains($detail, 'queue')) {
            return WhatsappNotification::STATUS_PENDING;
        }

        if (in_array($process, ['sent', 'delivered', 'success'], true)) {
            return WhatsappNotification::STATUS_SENT;
        }

        return WhatsappNotification::STATUS_PENDING;
    }
}
