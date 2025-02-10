<?php
// app/Services/SMSGateways/MocitSmsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS via Mocit API using SMS mode.
// Required parameters: secret, type="sms", message, phone, mode="devices", device, sim.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitSmsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $device = config('notificationgateways.mocit.device');
        $sim = config('notificationgateways.mocit.sim', 1);
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $client = new Client();
        $url = $gatewayUrl . '/send/sms';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'sms'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $to],
                    ['name' => 'mode', 'contents' => 'devices'],
                    ['name' => 'device', 'contents' => $device],
                    ['name' => 'sim', 'contents' => $sim],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
