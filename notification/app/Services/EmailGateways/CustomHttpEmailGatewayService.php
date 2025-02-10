<?php
// app/Services/EmailGateways/CustomHttpEmailGatewayService.php
// Last updated: 2025-02-06
// Sends email using a custom HTTP API endpoint.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CustomHttpEmailGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $url = env('CUSTOM_HTTP_EMAIL_URL', 'https://example.com/api/email');
        $client = new Client();
        try {
            $response = $client->post($url, [
                'form_params' => [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Custom HTTP Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
