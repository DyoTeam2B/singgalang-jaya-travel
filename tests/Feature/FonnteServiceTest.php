<?php

namespace Tests\Feature;

use App\Models\WhatsappNotification;
use App\Services\FonnteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FonnteServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_normalizes_phone_number_and_allows_offline_device_queue(): void
    {
        config([
            'services.fonnte.token' => 'test-token',
            'services.fonnte.url' => 'https://api.fonnte.com/send',
            'services.fonnte.country_code' => '62',
            'services.fonnte.connect_only' => false,
        ]);

        Http::fake([
            'api.fonnte.com/send' => Http::response([
                'status' => true,
                'detail' => 'success! message in queue',
                'process' => 'pending',
                'requestid' => 12345,
            ]),
        ]);

        $sent = app(FonnteService::class)->send(
            '0812-9999 8888',
            'Halo pelanggan',
            WhatsappNotification::TYPE_CUSTOM,
        );

        $this->assertTrue($sent);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.fonnte.com/send'
                && $request->hasHeader('Authorization', 'test-token')
                && $request['target'] === '6281299998888'
                && $request['message'] === 'Halo pelanggan'
                && $request['countryCode'] === '62'
                && $request['connectOnly'] === 'false';
        });

        $this->assertDatabaseHas('whatsapp_notifications', [
            'target' => '6281299998888',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_PENDING,
        ]);
    }

    public function test_it_marks_notification_sent_when_fonnte_process_is_sent(): void
    {
        config([
            'services.fonnte.token' => 'test-token',
            'services.fonnte.url' => 'https://api.fonnte.com/send',
            'services.fonnte.country_code' => '62',
            'services.fonnte.connect_only' => false,
        ]);

        Http::fake([
            'api.fonnte.com/send' => Http::response([
                'status' => true,
                'process' => 'sent',
                'requestid' => 12345,
            ]),
        ]);

        $sent = app(FonnteService::class)->send(
            '0812-9999 8888',
            'Halo pelanggan',
            WhatsappNotification::TYPE_CUSTOM,
        );

        $this->assertTrue($sent);

        $this->assertDatabaseHas('whatsapp_notifications', [
            'target' => '6281299998888',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_SENT,
        ]);
    }

    public function test_it_fails_without_sending_when_phone_number_is_invalid(): void
    {
        config([
            'services.fonnte.token' => 'test-token',
            'services.fonnte.url' => 'https://api.fonnte.com/send',
        ]);

        Http::fake();

        $sent = app(FonnteService::class)->send('-', 'Halo pelanggan', WhatsappNotification::TYPE_CUSTOM);

        $this->assertFalse($sent);
        Http::assertNothingSent();

        $this->assertDatabaseHas('whatsapp_notifications', [
            'target' => '-',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_FAILED,
        ]);
    }
}
