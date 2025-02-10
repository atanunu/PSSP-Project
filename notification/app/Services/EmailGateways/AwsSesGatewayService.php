<?php
// app/Services/EmailGateways/AwsSesGatewayService.php
// Last updated: 2025-02-06
// Sends email using AWS SES.

namespace App\Services\EmailGateways;

use App\Interfaces\EmailGatewayInterface;
use Aws\Ses\SesClient;
use Illuminate\Support\Facades\Log;

class AwsSesGatewayService implements EmailGatewayInterface
{
    public function send(string $to, string $subject, string $body): array
    {
        $client = new SesClient([
            'region' => config('notificationgateways.aws.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('notificationgateways.aws.key'),
                'secret' => config('notificationgateways.aws.secret'),
            ],
        ]);
        try {
            $result = $client->sendEmail([
                'Source' => config('notificationgateways.aws.ses_source_email'),
                'Destination' => ['ToAddresses' => [$to]],
                'Message' => [
                    'Subject' => ['Data' => $subject],
                    'Body' => ['Html' => ['Data' => $body]]
                ],
            ]);
            return ['status' => 'success', 'gateway' => 'aws_ses', 'messageId' => $result->get('MessageId')];
        } catch (\Exception $e) {
            Log::error("AWS SES Email error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
