<?php
// app/Services/OtpGateways/MocitWhatsappOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Mocit using WhatsApp parameters (OTP mode).
// Required: secret, type="WhatsApp", message, phone, expire, account.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitWhatsappOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $account = config('notificationgateways.mocit.whatsapp_account');
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $url = $gatewayUrl . '/send/otp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'WhatsApp'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $phone],
                    ['name' => 'expire', 'contents' => $expire],
                    ['name' => 'account', 'contents' => $account],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp OTP send error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
    public function verifyOtp(string $otp, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $url = config('notificationgateways.mocit.gateway_url') . '/get/otp';
        try {
            $response = $client->get($url, ['query' => ['secret' => $apiSecret, 'otp' => $otp]]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit WhatsApp OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
