<?php
// app/Services/PushGateways/WonderpushGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Wonderpush.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WonderpushGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $apiKey = env('WONDERPUSH_API_KEY');
        $client = new Client();
        $url = 'https://api.wonderpush.com/v1/notifications';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Wonderpush error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
