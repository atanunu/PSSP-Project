<?php
// app/Services/PushGateways/PushEngageGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using PushEngage.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PushEngageGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $apiKey = env('PUSHENGAGE_API_KEY');
        $client = new Client();
        $url = 'https://api.pushengage.com/v1/notification/send';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'recipient' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("PushEngage error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
