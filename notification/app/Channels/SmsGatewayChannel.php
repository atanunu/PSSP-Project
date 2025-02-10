<?php
// app/Channels/SmsGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending SMS notifications using NotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\NotificationService;

class SmsGatewayChannel
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Called when a notification is sent via the SMS channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }
        $data = $notification->toSms($notifiable);
        $to = $data['phone'] ?? $notifiable->routeNotificationFor('sms');
        $message = $data['message'] ?? '';
        $gateway = $data['gateway'] ?? null;
        return $this->notificationService->sendSms($to, $message, $gateway);
    }
}
