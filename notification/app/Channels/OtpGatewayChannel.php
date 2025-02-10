<?php
// app/Channels/OtpGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending OTP notifications using OtpService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\OtpService;

class OtpGatewayChannel
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // Called when a notification is sent via the OTP channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toOtp')) {
            return;
        }
        $data = $notification->toOtp($notifiable);
        $phone = $data['phone'] ?? $notifiable->routeNotificationFor('otp');
        $message = $data['message'] ?? '';
        $expire = $data['expire'] ?? 300;
        $gateway = $data['gateway'] ?? null;
        return $this->otpService->sendOtp($phone, $message, $expire, $gateway);
    }
}
