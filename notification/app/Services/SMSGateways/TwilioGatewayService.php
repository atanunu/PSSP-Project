<?php
// app/Services/SMSGateways/TwilioGatewayService.php
// Last updated: 2025-02-06
// Sends SMS using the Twilio API.

namespace App\Services\SMSGateways;

use App\Interfaces\SmsGatewayInterface;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioGatewayService implements SmsGatewayInterface
{
    public function send(string $to, string $message): array
    {
        $sid   = config('notificationgateways.twilio.sid');
        $token = config('notificationgateways.twilio.token');
        $from  = config('notificationgateways.twilio.from');
        try {
            $client = new Client($sid, $token);
            $msg = $client->messages->create($to, ['from' => $from, 'body' => $message]);
            return ['status' => 'success', 'gateway' => 'twilio', 'messageSid' => $msg->sid];
        } catch (\Exception $e) {
            Log::error("Twilio SMS error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
