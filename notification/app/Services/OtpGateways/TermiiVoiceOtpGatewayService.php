<?php
// app/Services/OtpGateways/TermiiVoiceOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Termii Voice using Termii's voice token API.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TermiiVoiceOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $to, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $senderId = config('notificationgateways.termii.sender_id');
        $url = 'https://api.ng.termii.com/api/voice/otp/send';
        $params = [
            'api_key' => $apiKey,
            'to' => $to,
            'from' => $senderId,
            'voice' => $message,
        ];
        try {
            $response = $client->post($url, ['form_params' => $params]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Voice OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiKey = config('notificationgateways.termii.api_key');
        $url = 'https://api.ng.termii.com/api/sms/otp/verify';
        if (!isset($options['phone'])) {
            $errorMsg = "Termii OTP verify error: 'phone' parameter is required.";
            Log::error($errorMsg);
            return ['status' => 'error', 'error' => $errorMsg];
        }
        $phone = $options['phone'];
        try {
            $response = $client->post($url, ['form_params' => [
                'api_key' => $apiKey,
                'otp' => $otp,
                'to' => $phone,
            ]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Termii Voice OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
