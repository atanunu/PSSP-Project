<?php
// app/Services/EmailGateways/MailgunGatewayService.php
// Last updated: 2025-02-06
// Sends email using the Mailgun API.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MailgunGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $domain = config('notificationgateways.mailgun.domain');
        $apiKey = config('notificationgateways.mailgun.secret');
        $url = "https://api.mailgun.net/v3/{$domain}/messages";
        $client = new Client();
        try {
            $response = $client->post($url, [
                'auth' => ['api', $apiKey],
                'form_params' => [
                    'from' => "Mailgun Sandbox <postmaster@{$domain}>",
                    'to' => $to,
                    'subject' => $subject,
                    'text' => $body,
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mailgun Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
