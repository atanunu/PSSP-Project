<?php
// app/Channels/EmailGatewayChannel.php
// Last updated: 2025-02-06
// Custom channel for sending Email notifications using NotificationService.

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\NotificationService;

class EmailGatewayChannel
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Called when a notification is sent via the Email channel.
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toEmail')) {
            return;
        }
        $data = $notification->toEmail($notifiable);
        $to = $data['email'] ?? $notifiable->routeNotificationFor('email');
        $subject = $data['subject'] ?? '';
        $body = $data['body'] ?? '';
        $gateway = $data['gateway'] ?? null;
        return $this->notificationService->sendEmail($to, $subject, $body, $gateway);
    }
}
