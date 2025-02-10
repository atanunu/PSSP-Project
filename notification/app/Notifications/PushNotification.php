<?php
// app/Notifications/PushNotification.php
// Last updated: 2025-02-06
// Notification class for sending push notifications.

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Channels\PushNotificationChannel;

class PushNotification extends Notification
{
    protected array $payload;
    protected ?string $gateway;

    public function __construct(array $payload, ?string $gateway = null)
    {
        $this->payload = $payload;
        $this->gateway = $gateway;
    }

    // Specifies the channels to use.
    public function via($notifiable): array
    {
        return [PushNotificationChannel::class];
    }

    // Formats the data for the Push channel.
    public function toPush($notifiable): array
    {
        return [
            'to' => $notifiable->routeNotificationFor('push'),
            'payload' => $this->payload,
            'gateway' => $this->gateway,
        ];
    }
}
