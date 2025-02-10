<?php
// app/Notifications/OtpNotification.php
// Last updated: 2025-02-06
// Notification class for sending OTP messages.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\OtpGatewayChannel;

class OtpNotification extends Notification
{
    protected string $message;
    protected int $expire;
    protected ?string $gateway;

    public function __construct(string $message, int $expire = 300, ?string $gateway = null)
    {
        $this->message = $message;
        $this->expire = $expire;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [OtpGatewayChannel::class];
    }

    // Formats the data for the OTP channel.
    public function toOtp($notifiable): array
    {
        return [
            'phone' => $notifiable->routeNotificationFor('otp'),
            'message' => $this->message,
            'expire' => $this->expire,
            'gateway' => $this->gateway,
        ];
    }
}
