<?php
// app/Services/SMSGateways/AfricasTalkingGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using Africa's Talking API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AfricasTalkingGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $username = config('notificationgateways.africastalking.username');
        $apiKey   = config('notificationgateways.africastalking.api_key');
        $url      = 'https://api.africastalking.com/version1/messaging';
        $client   = new Client();
        try {
            $response = $client->post($url, [
                'headers' => ['apiKey' => $apiKey],
                'form_params' => [
                    'username' => $username,
                    'to' => $to,
                    'message' => $message,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Africa's Talking SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
