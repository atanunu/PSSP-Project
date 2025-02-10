<?php
// app/Services/OtpGateways/MocitSmsOtpGatewayService.php
// Last updated: 2025-02-06
// Sends OTP via Mocit using SMS parameters (OTP mode).
// Required: secret, type="sms", message, phone, expire, mode="devices", device, sim.

namespace App\Services\OtpGateways;

use App\Interfaces\OtpGatewayInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MocitSmsOtpGatewayService implements OtpGatewayInterface
{
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array
    {
        $client = new Client();
        $apiSecret = config('notificationgateways.mocit.api_secret');
        $device = config('notificationgateways.mocit.device');
        $sim = config('notificationgateways.mocit.sim', 1);
        $gatewayUrl = config('notificationgateways.mocit.gateway_url');
        $url = $gatewayUrl . '/send/otp';
        try {
            $response = $client->post($url, [
                'multipart' => [
                    ['name' => 'secret', 'contents' => $apiSecret],
                    ['name' => 'type', 'contents' => 'sms'],
                    ['name' => 'message', 'contents' => $message],
                    ['name' => 'phone', 'contents' => $phone],
                    ['name' => 'expire', 'contents' => $expire],
                    ['name' => 'mode', 'contents' => 'devices'],
                    ['name' => 'device', 'contents' => $device],
                    ['name' => 'sim', 'contents' => $sim],
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error("Mocit SMS OTP send error: " . $e->getMessage());
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
            Log::error("Mocit SMS OTP verify error: " . $e->getMessage());
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }
}
