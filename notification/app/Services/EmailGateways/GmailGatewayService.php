<?php
// app/Services/EmailGateways/GmailGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Gmail API (simulated).

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GmailGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $accessToken = config('notificationgateways.gmail.access_token');
        $client = new Client();
        try {
            $response = $client->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Gmail Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
