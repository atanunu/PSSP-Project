<?php
// app/Services/EmailGateways/SendgridGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Sendgrid API.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SendgridGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $apiKey = config('notificationgateways.sendgrid.api_key');
        $url = 'https://api.sendgrid.com/v3/mail/send';
        $client = new Client();
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'personalizations' => [
                        [
                            'to' => [['email' => $to]],
                            'subject' => $subject,
                        ],
                    ],
                    'from' => ['email' => config('notificationgateways.sendgrid.from')],
                    'content' => [
                        [
                            'type' => 'text/html',
                            'value' => $body,
                        ],
                    ],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Sendgrid Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
