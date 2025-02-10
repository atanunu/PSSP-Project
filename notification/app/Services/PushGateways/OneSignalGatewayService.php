<?php
// app/Services/PushGateways/OneSignalGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using OneSignal.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OneSignalGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $appId = env('ONESIGNAL_APP_ID');
        $restApiKey = env('ONESIGNAL_REST_API_KEY');
        $client = new Client();
        $url = 'https://onesignal.com/api/v1/notifications';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . $restApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'app_id' => $appId,
                    'include_player_ids' => is_array($to) ? $to : [$to],
                    'headings' => ['en' => $payload['title'] ?? ''],
                    'contents' => ['en' => $payload['body'] ?? ''],
                    'data' => $payload['data'] ?? []
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("OneSignal error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
