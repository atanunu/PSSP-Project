<?php
// app/Services/PushGateways/GoogleFcmGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Google FCM.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleFcmGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $serverKey = env('GOOGLE_FCM_SERVER_KEY');
        $client = new Client();
        $url = 'https://fcm.googleapis.com/fcm/send';
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Google FCM error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
