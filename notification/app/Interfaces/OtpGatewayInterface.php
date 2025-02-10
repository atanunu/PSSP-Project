<?php
// app/Interfaces/OtpGatewayInterface.php
// Last updated: 2025-02-06
// Interface for OTP gateway services.

namespace App\Interfaces;

interface OtpGatewayInterface
{
    /**
     * Send an OTP message.
     *
     * @param string $phone Recipient phone number.
     * @param string $message OTP message with the {{otp}} shortcode.
     * @param int $expire Expiration time in seconds.
     * @param array $options Additional parameters.
     * @return array Response data from the gateway.
     */
    public function sendOtp(string $phone, string $message, int $expire, array $options = []): array;

    /**
     * Verify an OTP.
     *
     * @param string $otp The OTP provided by the user.
     * @param array $options Additional parameters (e.g., phone number).
     * @return array Response data from the gateway.
     */
    public function verifyOtp(string $otp, array $options = []): array;
}
