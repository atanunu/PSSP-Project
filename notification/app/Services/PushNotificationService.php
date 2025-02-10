<?php
// app/Services/PushNotificationService.php
// Last updated: 2025-02-06
// Provides a common interface for sending push notifications via the appropriate push gateway.

namespace App\Services;

use App\Interfaces\PushGatewayInterface;
use App\Services\PushGateways\GoogleFcmGatewayService;
use App\Services\PushGateways\OneSignalGatewayService;
use App\Services\PushGateways\WonderpushGatewayService;
use App\Services\PushGateways\AirshipGatewayService;
use App\Services\PushGateways\PushEngageGatewayService;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    // Sends a push notification using the specified gateway.
    public function sendPush($to, array $payload, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_push_gateway');
        $pushGateway = $this->resolvePushGateway($gateway);
        return $pushGateway->sendPush($to, $payload);
    }
    
    // Resolves the push gateway instance.
    protected function resolvePushGateway(string $gateway): PushGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'google_fcm':
                return new GoogleFcmGatewayService();
            case 'onesignal':
                return new OneSignalGatewayService();
            case 'wonderpush':
                return new WonderpushGatewayService();
            case 'airship':
                return new AirshipGatewayService();
            case 'pushengage':
                return new PushEngageGatewayService();
            default:
                return new GoogleFcmGatewayService();
        }
    }
}
