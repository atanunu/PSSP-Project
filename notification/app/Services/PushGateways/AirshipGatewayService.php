<?php
// app/Services/PushGateways/AirshipGatewayService.php
// Last updated: 2025-02-06
// Sends push notifications using Airship.

namespace App\Services\PushGateways;

use App\Interfaces\PushGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AirshipGatewayService implements PushGatewayInterface
{
    public function sendPush($to, array $payload): array
    {
        $appKey = env('AIRSHIP_APP_KEY');
        $masterSecret = env('AIRSHIP_MASTER_SECRET');
        $client = new Client();
        $url = 'https://go.urbanairship.com/api/push/';
        try {
            $response = $client->post($url, [
                'auth' => [$appKey, $masterSecret],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'audience' => $to,
                    'notification' => $payload,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Airship error: " . $e->getMessage());
            return ['status' => 'failed', 'response_code' => '09', 'error' => $e->getMessage()];
        }
    }
}
