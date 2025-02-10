<?php
// app/Services/SMSGateways/AwsSnsGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using AWS SNS.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Log;

class AwsSnsGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $client = new SnsClient([
            'region' => config('notificationgateways.aws.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('notificationgateways.aws.key'),
                'secret' => config('notificationgateways.aws.secret'),
            ],
        ]);
        try {
            $result = $client->publish(['Message' => $message, 'PhoneNumber' => $to]);
            return ['status' => 'success', 'gateway' => 'aws_sns', 'messageId' => $result->get('MessageId')];
        } catch (\Exception $e) {
            Log::error("AWS SNS SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
