<?php
// app/Channels/PushNotificationChannel.php
// Last updated: 2025-02-06
// Custom channel for sending push notifications using PushNotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\PushNotificationService;

class PushNotificationChannel
{
    protected PushNotificationService $pushNotificationService;

    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }

    // Called when a notification is sent via the Push channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toPush')) {
            return;
        }
        $data = $notification->toPush($notifiable);
        $to = $data['to'] ?? $notifiable->routeNotificationFor('push');
        $payload = $data['payload'] ?? [];
        $gateway = $data['gateway'] ?? null;
        return $this->pushNotificationService->sendPush($to, $payload, $gateway);
    }
}
