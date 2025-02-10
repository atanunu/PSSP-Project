<?php
// app/Services/SMSGateways/TermiiSmsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using Termii's send token API (for SMS).
// Note: Termii splitting does not apply to the SMS gateway.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiSmsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/sms/send';
        $params = [
            'api_key' => $apiKey,
            'to' => $to,
            'from' => $senderId,
            'sms' => $message,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
