<?php
// app/Services/SMSGateways/HttpGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using a generic HTTP API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HttpGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new Client();
        $url = config('notificationgateways.http_sms_url');
        try {
            $response = $client->post($url, [
                'form_params' => ['to' => $to, 'message' => $message],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("HTTP SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
