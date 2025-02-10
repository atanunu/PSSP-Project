<?php
// app/Services/SMSGateways/MocitWhatsappGatewayService.php
// Last updated: 2025-02-06
// Sends SMS via Mocit API using WhatsApp mode.
// Required parameters: secret, type="WhatsApp", message, phone, expire, account.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitWhatsappGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $account = config('notificationgateways.mocit.whatsapp_account');
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $client = new Client();
        $url = $gatewayUrl . '/send/whatsapp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'WhatsApp'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $to],
                    ['name' => 'account', 'contents' => $account],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
