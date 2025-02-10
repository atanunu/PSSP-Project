<?php
// app/Services/OtpService.php
// Last updated: 2025-02-06
// Handles sending and verifying OTPs via the appropriate OTP gateway.

namespace App\Services;

use App\Models\NotificationLog;
use App\Interfaces\OtpGatewayInterface;
use Illuminate\Support\Facades\Log;

class OtpService
{
    // Sends an OTP notification.
    public function sendOtp(string $phone, string $message, int $expire, ?string $gateway = null): array
    {
        $gateway = $gateway ?? config('notifications.default_otp_gateway', 'mocit_sms');
        $otpGateway = $this->resolveOtpGateway($gateway);
        $response = $otpGateway->sendOtp($phone, $message, $expire);
        NotificationLog::create([
            'type'      => 'otp',
            'gateway'   => $gateway,
            'recipient' => $phone,
            'message'   => $message,
            'response'  => json_encode($response),
        ]);
        return $response;
    }

    // Verifies an OTP.
    public function verifyOtp(string $otp, ?string $gateway = null, array $options = []): array
    {
        $gateway = $gateway ?? config('notifications.default_otp_gateway', 'mocit_sms');
        $otpGateway = $this->resolveOtpGateway($gateway);
        return $otpGateway->verifyOtp($otp, $options);
    }

    // Resolves the OTP gateway instance.
    protected function resolveOtpGateway(string $gateway): OtpGatewayInterface
    {
        switch (strtolower($gateway)) {
            case 'termii':
                return new \App\Services\OtpGateways\TermiiSmsOtpGatewayService();
            case 'termii_whatsapp':
                return new \App\Services\OtpGateways\TermiiWhatsappOtpGatewayService();
            case 'termii_email':
                return new \App\Services\OtpGateways\TermiiEmailOtpGatewayService();
            case 'termii_voice':
                return new \App\Services\OtpGateways\TermiiVoiceOtpGatewayService();
            case 'mocit_whatsapp':
                return new \App\Services\OtpGateways\MocitWhatsappOtpGatewayService();
            case 'mocit_sms':
            default:
                return new \App\Services\OtpGateways\MocitSmsOtpGatewayService();
        }
    }
}
