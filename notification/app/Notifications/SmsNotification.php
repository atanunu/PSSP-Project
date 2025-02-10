<?php
// app/Notifications/SmsNotification.php
// Last updated: 2025-02-06
// Notification class for sending SMS messages.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\SmsGatewayChannel;

class SmsNotification extends Notification
{
    protected string $message;
    protected ?string $gateway;

    public function __construct(string $message, ?string $gateway = null)
    {
        $this->message = $message;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [SmsGatewayChannel::class];
    }

    // Formats the data for the SMS channel.
    public function toSms($notifiable): array
    {
        return [
            'phone' => $notifiable->routeNotificationFor('sms'),
            'message' => $this->message,
            'gateway' => $this->gateway,
        ];
    }
}
